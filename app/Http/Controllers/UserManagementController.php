<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Course;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Shift;
use App\Models\Side;
use App\Models\User;
use App\Models\UserStudent;
use App\Support\EmailDomainValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserManagementController extends Controller
{
    private const TEACHER_NOTICE = 'Professores não entram direto na turma: são vinculados por meio das disciplinas da turma.';

    public function index(Request $request)
    {
        return view('users.index', $this->viewData($request));
    }

    public function filtrar(Request $request)
    {
        return view('users.partials.table', $this->viewData($request));
    }

    private function viewData(Request $request): array
    {
        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        return [
            'usuarios' => $this->usuariosFiltrados($request),
            'schoolClasses' => SchoolClass::whereIn('etec_id', $etecIds)
                ->with(['course', 'grade', 'shift'])
                ->get(),
            'grades' => Grade::all(),
            'courses' => Course::all(),
            'shifts' => Shift::all(),
        ];
    }

    private function usuariosFiltrados(Request $request)
    {
        $etecIds = auth()->user()->etecs()->pluck('etecs.id');
        $cargo = Role::tryFrom((string) ($request->cargo ?? $request->role));

        // Aluno: pela matrícula (user_students -> turma). Professor: pela pivot school_class_user.
        $inClass = fn($query, string $column, $value) => $query->where(
            fn($sub) => $sub
                ->whereHas(
                    'student.schoolClass',
                    fn($s) => $s->where("school_classes.$column", $value),
                )
                ->orWhereHas(
                    'schoolClasses',
                    fn($t) => $t->where("school_classes.$column", $value),
                ),
        );

        return User::whereHas('etecs', fn($q) => $q->whereIn('etecs.id', $etecIds))
            ->with('etecs')
            ->when($cargo, fn($q) => $q->where('role', $cargo->value))
            ->when($request->rm, function ($q) use ($request, $etecIds) {
                $q->whereHas(
                    'etecs',
                    fn($sub) => $sub
                        ->whereIn('etecs.id', $etecIds)
                        ->where('etec_worker.rm', $request->rm),
                );
            })
            ->when(
                $request->school_class_id,
                fn($q) => $inClass($q, 'id', $request->school_class_id),
            )
            ->when($request->grade_id, fn($q) => $inClass($q, 'grade_id', $request->grade_id))
            ->when($request->course_id, fn($q) => $inClass($q, 'course_id', $request->course_id))
            ->get();
    }

    public function edit(User $user)
    {
        $this->authorize('manage', $user);

        $userStudent = $user->isStudent() ? $user->student : null;

        return view('users.edit', [
            'user' => $user,
            'sides' => Side::all(),
            'userStudent' => $userStudent,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('manage', $user);

        $etec = auth()->user()->activeEtec();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => ['sometimes', 'in:aluno,professor,coordenador'],
            'id_side' => ['nullable', 'exists:side,id_side'],
        ];

        if ($user->isStudent()) {
            // Aluno cadastrado em lote não tem e-mail: ele é opcional, mas se vier precisa ser válido.
            $rules['email'] = [
                'nullable',
                'email',
                'unique:users,email,' . $user->id,
                function ($attribute, $value, $fail) {
                    if (!EmailDomainValidator::isValid($value, Role::Aluno)) {
                        $fail('Use o e-mail institucional do aluno.');
                    }
                },
            ];

            $rules['school_class_id'] = [
                'nullable',
                Rule::exists('school_classes', 'id')->where('etec_id', $etec?->id),
            ];
        }

        $validated = $request->validate($rules);

        $user->update(
            collect($validated)
                ->only(['name', 'email', 'role'])
                ->all(),
        );

        if ($user->isStudent()) {
            $changes = [];

            // Só altera o lado se ele foi enviado, para não apagar o padrão ("Composto").
            if ($request->filled('id_side')) {
                $changes['id_side'] = $validated['id_side'];
            }

            if ($request->has('school_class_id')) {
                $changes['id_class'] = $validated['school_class_id'] ?? null;
            }

            if ($changes) {
                $this->studentRecord($user, $etec?->id)->fill($changes)->save();
            }
        }

        return redirect()->route('users.index')->with('status', 'Usuário atualizado!');
    }

    public function addToClass(Request $request, User $user)
    {
        abort_if($user->role === Role::Coordenador, 403);

        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        abort_unless($user->etecs()->whereIn('etecs.id', $etecIds)->exists(), 403);

        // Professor não entra direto na turma: o vínculo é feito pela disciplina.
        if (!$user->isStudent()) {
            return back()->with('status', self::TEACHER_NOTICE);
        }

        $request->validate($this->classRules());

        $this->enrollStudents(
            collect([$user]),
            $this->authorizedSchoolClass((int) $request->input('school_class_id')),
        );

        return back()->with('status', 'Aluno adicionado à turma!');
    }

    public function addToClassMultiple(Request $request)
    {
        $request->validate([
            'usuarios' => ['required', 'array', 'min:1'],
            'usuarios.*' => ['exists:users,id'],
            ...$this->classRules(),
        ]);

        $schoolClass = $this->authorizedSchoolClass((int) $request->input('school_class_id'));

        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        $users = User::whereIn('id', $request->usuarios)
            ->whereHas('etecs', fn($q) => $q->whereIn('etecs.id', $etecIds))
            ->get();

        $students = $users->filter(fn($u) => $u->isStudent());

        $this->enrollStudents($students, $schoolClass);

        $message = $students->count() . ' aluno(s) adicionado(s) à turma!';

        $ignored = $users->count() - $students->count();

        if ($ignored > 0) {
            $message .= " {$ignored} usuário(s) ignorado(s). " . self::TEACHER_NOTICE;
        }

        return back()->with('status', $message);
    }

    private function classRules(): array
    {
        return [
            'school_class_id' => ['required', 'exists:school_classes,id'],
        ];
    }

    private function authorizedSchoolClass(int $schoolClassId): SchoolClass
    {
        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        $schoolClass = SchoolClass::findOrFail($schoolClassId);

        abort_unless($etecIds->contains($schoolClass->etec_id), 403);

        return $schoolClass;
    }

    private function enrollStudents(Collection $students, SchoolClass $schoolClass): void
    {
        foreach ($students as $student) {
            $this->studentRecord($student, $schoolClass->etec_id)
                ->fill(['id_class' => $schoolClass->id])
                ->save();
        }
    }

    /**
     * Matrícula existente do aluno, ou uma nova (ainda não salva) com o RM da Etec.
     */
    private function studentRecord(User $user, ?int $etecId): UserStudent
    {
        return $user->student ??
            new UserStudent([
                'user_id' => $user->id,
                'rm' =>
                    (int) ($user->etecs()->where('etecs.id', $etecId)->first()?->pivot->rm ?? 0),
            ]);
    }

    public function destroy(Request $request, User $user)
    {
        $this->authorize('manage', $user);
        $this->authorizeDeletion($request, [$user->id]);

        $user->delete();

        return redirect()->route('users.index')->with('status', 'Usuário removido!');
    }

    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['exists:users,id'],
        ]);

        $etecIds = auth()->user()->etecs()->pluck('etecs.id');
        $requested = array_unique($request->input('ids'));

        $allowed = User::whereIn('id', $requested)
            ->whereHas('etecs', fn($q) => $q->whereIn('etecs.id', $etecIds))
            ->pluck('id')
            ->all();

        abort_unless(count($allowed) === count($requested), 403);

        $this->authorizeDeletion($request, $allowed);

        User::whereIn('id', $allowed)->where('role', '!=', Role::Coordenador->value)->delete();

        return redirect()->route('users.index')->with('status', 'Usuários removidos!');
    }

    private function authorizeDeletion(Request $request, array $ids): void
    {
        $request->validate(
            [
                'password' => ['required', 'string'],
            ],
            [
                'password.required' => 'Por favor, confirme sua senha para continuar.',
            ],
        );

        if (!Hash::check($request->input('password'), Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => 'Senha incorreta.',
            ]);
        }

        $existeCoordenador = User::whereIn('id', $ids)
            ->where('role', Role::Coordenador->value)
            ->exists();

        if ($existeCoordenador) {
            abort(403, 'Os coordenadores não podem ser removidos aqui.');
        }
    }
}
