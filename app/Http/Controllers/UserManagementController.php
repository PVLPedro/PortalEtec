<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use App\Models\Etec;
use App\Models\SchoolClass;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Shift;
use App\Policies\UserPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        return view('users.index', [
            'usuarios' => $this->usuariosFiltrados($request),
            'schoolClasses' => SchoolClass::whereIn('etec_id', $etecIds)->get(),
            'grades' => Grade::all(),
            'courses' => Course::all(),
            'shifts' => Shift::all(),
        ]);
    }

    public function filtrar(Request $request)
    {
        $usuarios = $this->usuariosFiltrados($request);

        return view('users.partials.table', compact('usuarios'));
    }

    private function usuariosFiltrados(Request $request)
    {
        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        return User::whereHas('etecs', fn($q) => $q->whereIn('etecs.id', $etecIds))
            ->when($request->cargo, fn($q) => $q->where('role', $request->cargo))
            ->when($request->rm, function ($q) use ($request, $etecIds) {
                $q->whereHas(
                    'etecs',
                    fn($sub) => $sub->whereIn('etecs.id', $etecIds)->where('rm', $request->rm),
                );
            })
            ->when(
                $request->school_class_id,
                fn($q) => $q->whereHas(
                    'schoolClasses',
                    fn($sub) => $sub->where('school_classes.id', $request->school_class_id),
                ),
            )
            ->when(
                $request->grade_id,
                fn($q) => $q->whereHas(
                    'schoolClasses',
                    fn($sub) => $sub->where('grade_id', $request->grade_id),
                ),
            )
            ->when(
                $request->course_id,
                fn($q) => $q->whereHas(
                    'schoolClasses',
                    fn($sub) => $sub->where('course_id', $request->course_id),
                ),
            )
            ->get();
    }

    public function edit(User $user)
    {
        $this->authorize('manage', $user);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:aluno,professor,coordenador'],
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('status', 'User updated!');
    }

    public function addToClass(Request $request, User $user)
    {
        abort_if($user->role === Role::Coordenador, 403);

        $request->validate([
            'school_class_id' => ['required', 'exists:school_classes,id'],
        ]);

        $schoolClass = $this->authorizedSchoolClass($request->school_class_id);

        $schoolClass->users()->syncWithoutDetaching([$user->id]);

        return back()->with('status', 'Usuário adicionado à turma!');
    }

    public function addToClassMultiple(Request $request)
    {
        $request->validate([
            'usuarios' => ['required', 'array', 'min:1'],
            'usuarios.*' => ['exists:users,id'],
            'school_class_id' => ['required', 'exists:school_classes,id'],
        ]);

        $schoolClass = $this->authorizedSchoolClass($request->school_class_id);

        $schoolClass->users()->syncWithoutDetaching($request->usuarios);

        return back()->with('status', 'Usuários adicionados à turma!');
    }

    private function authorizedSchoolClass(int $schoolClassId): SchoolClass
    {
        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        $schoolClass = SchoolClass::findOrFail($schoolClassId);
        abort_unless($etecIds->contains($schoolClass->etec_id), 403);

        return $schoolClass;
    }

    public function destroy(Request $request, User $user)
    {
        $this->authorizeDeletion($request, [$user->id]);

        $user->delete();

        return redirect()->route('users.index')->with('status', 'User removed!');
    }

    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['exists:users,id'],
        ]);

        $ids = $request->input('ids');

        $this->authorizeDeletion($request, $ids);

        User::whereIn('id', $ids)->where('role', '!=', Role::Coordenador->value)->delete();

        return redirect()->route('users.index')->with('status', 'Users removed!');
    }

    /**
     * Confirms the logged-in coordinator's password and ensures no
     * coordinator is among the users being deleted.
     */
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
