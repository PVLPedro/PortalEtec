<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Shift;
use App\Support\EmailDomainValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserManagementController extends Controller
{
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
            'schoolClasses' => SchoolClass::whereIn('etec_id', $etecIds)->get(),
            'grades' => Grade::all(),
            'courses' => Course::all(),
            'shifts' => Shift::all(),
        ];
    }

    private function usuariosFiltrados(Request $request)
    {
        $etecIds = auth()->user()->etecs()->pluck('etecs.id');
        $cargo = Role::tryFrom((string) ($request->cargo ?? $request->role));

        $inClass = fn($query, callable $constraint) => $query->where(
            fn($group) => $group
                ->whereHas('schoolClasses', $constraint)
                ->orWhereHas('studentClasses', $constraint),
        );

        return User::whereHas('etecs', fn($q) => $q->whereIn('etecs.id', $etecIds))
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
                fn($q) => $inClass(
                    $q,
                    fn($sub) => $sub->where('school_classes.id', $request->school_class_id),
                ),
            )
            ->when(
                $request->grade_id,
                fn($q) => $inClass($q, fn($sub) => $sub->where('grade_id', $request->grade_id)),
            )
            ->when(
                $request->course_id,
                fn($q) => $inClass($q, fn($sub) => $sub->where('course_id', $request->course_id)),
            )
            ->get();
    }

    public function edit(User $user)
    {
        $this->authorize('manage', $user);

        $etec = auth()->user()->activeEtec();

        $rm = $user->etecs()->where('etecs.id', $etec?->id)->first()?->pivot->rm;

        $classes = SchoolClass::where('etec_id', $etec?->id)
            ->with(['course', 'grade', 'shift'])
            ->get()
            ->sortBy('nome')
            ->values();

        $currentClassId = $user->studentClasses()->value('school_classes.id');

        return view('users.edit', compact('user', 'rm', 'classes', 'currentClassId'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('manage', $user);

        $etec = auth()->user()->activeEtec();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        ];

        if ($user->isStudent()) {
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
                ->only(['name', 'email'])
                ->all(),
        );

        if ($user->isStudent()) {
            $classId = $validated['school_class_id'] ?? null;
            $user->studentClasses()->sync($classId ? [$classId] : []);
        }

        return redirect()->route('users.index')->with('status', 'Usuário atualizado!');
    }

    public function addToClass(Request $request)
    {
        $etecIds = auth()->user()->etecs()->pluck('etecs.id');
        $etec = auth()->user()->activeEtec();

        $request->validate([
            'usuarios' => ['required', 'array', 'min:1'],
            'school_class_id' => [
                'required_without:nova_turma.course_id',
                'nullable',
                'exists:school_classes,id',
            ],
            'nova_turma.course_id' => [
                'required_without:school_class_id',
                'nullable',
                'exists:courses,id',
            ],
            'nova_turma.grade_id' => [
                'required_with:nova_turma.course_id',
                'nullable',
                'exists:grades,id',
            ],
            'nova_turma.shift_id' => [
                'required_with:nova_turma.course_id',
                'nullable',
                'exists:shifts,id',
            ],
        ]);

        if ($request->filled('nova_turma.course_id')) {
            abort_unless($etec, 403);

            $schoolClass = SchoolClass::create([
                'etec_id' => $etec->id,
                'course_id' => $request->input('nova_turma.course_id'),
                'grade_id' => $request->input('nova_turma.grade_id'),
                'shift_id' => $request->input('nova_turma.shift_id'),
            ]);
        } else {
            $schoolClass = SchoolClass::findOrFail($request->school_class_id);
            abort_unless($etecIds->contains($schoolClass->etec_id), 403);
        }

        $users = User::whereIn('id', $request->usuarios)
            ->whereHas('etecs', fn($q) => $q->whereIn('etecs.id', $etecIds))
            ->get();

        $studentIds = $users->filter(fn($u) => $u->isStudent())->pluck('id')->all();

        $schoolClass->students()->syncWithoutDetaching($studentIds);

        $ignored = $users->count() - count($studentIds);

        $message = count($studentIds) . ' aluno(s) adicionado(s) à turma.';

        if ($ignored > 0) {
            $message .= " {$ignored} professor(es)/coordenador(es) não foram adicionados: o vínculo de professor com a turma exige disciplina.";
        }

        return back()->with('status', $message);
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
