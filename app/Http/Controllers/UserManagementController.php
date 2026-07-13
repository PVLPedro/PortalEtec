<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use App\Models\Etec;
use App\Policies\UserPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        return view('users.index', [
            'usuarios' => $this->usuariosFiltrados($request),
            // 'turmas' => Turma::whereIn('etec_id', auth()->user()->etecs()->pluck('etecs.id'))->get(),
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
            // ->when(
            //     $request->turma_id,
            //     fn($q) => $q->whereHas(
            //         'turmas',
            //         fn($sub) => $sub->where('turmas.id', $request->turma_id),
            //     ),
            // )
            // ->when(
            //     $request->serie,
            //     fn($q) => $q->whereHas('turmas', fn($sub) => $sub->where('serie', $request->serie)),
            // )
            // ->when(
            //     $request->curso,
            //     fn($q) => $q->whereHas('turmas', fn($sub) => $sub->where('curso', $request->curso)),
            // )
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
}
