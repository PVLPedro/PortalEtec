<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use App\Models\Etec;
use App\Models\SchoolClass;
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
            'series' => SchoolClass::whereIn('etec_id', $etecIds)->distinct()->pluck('serie'),
            'cursos' => SchoolClass::whereIn('etec_id', $etecIds)->distinct()->pluck('curso'),
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
                $request->turma_id,
                fn($q) => $q->whereHas(
                    'turmas',
                    fn($sub) => $sub->where('turmas.id', $request->turma_id),
                ),
            )
            ->when(
                $request->serie,
                fn($q) => $q->whereHas('turmas', fn($sub) => $sub->where('serie', $request->serie)),
            )
            ->when(
                $request->curso,
                fn($q) => $q->whereHas('turmas', fn($sub) => $sub->where('curso', $request->curso)),
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
            'cpf' => ['required', 'digits:11', 'unique:users,cpf,' . $user->id],
            'phone' => ['required', 'regex:/^\d{2}9\d{8}$/'],
            'role' => ['required', 'in:aluno,professor,coordenador'],
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('status', 'User updated!');
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
                'password.required' => 'Please confirm your password to continue.',
            ],
        );

        if (!Hash::check($request->input('password'), Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => 'Incorrect password.',
            ]);
        }

        $existeCoordenador = User::whereIn('id', $ids)
            ->where('role', Role::Coordenador->value)
            ->exists();

        if ($existeCoordenador) {
            abort(403, 'A coordinator cannot be deleted here.');
        }
    }

    public function addToClass(Request $request)
    {
        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        $request->validate([
            'usuarios' => ['required', 'array', 'min:1'],
            'school_class_id' => [
                'required_without:nova_turma.curso',
                'nullable',
                'exists:school_classes,id',
            ],
            'nova_turma.curso' => ['required_without:school_class_id', 'nullable', 'string'],
            'nova_turma.serie' => ['required_with:nova_turma.curso', 'nullable', 'string'],
            'nova_turma.turno' => ['required_with:nova_turma.curso', 'nullable', 'string'],
        ]);

        if ($request->filled('nova_turma.curso')) {
            $schoolClass = SchoolClass::create([
                'etec_id' => $etecIds->first(),
                'curso' => $request->input('nova_turma.curso'),
                'serie' => $request->input('nova_turma.serie'),
                'turno' => $request->input('nova_turma.turno'),
            ]);
        } else {
            $schoolClass = SchoolClass::findOrFail($request->school_class_id);
            abort_unless($etecIds->contains($schoolClass->etec_id), 403);
        }

        $schoolClass->users()->syncWithoutDetaching($request->usuarios);

        return back()->with('status', 'Usuários adicionados à turma!');
    }
}
