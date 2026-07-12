<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use App\Models\Etec;
use App\Policies\UserPolicy;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        $etecIds = auth()->user()->etecs()->pluck('etecs.id');

        $usuarios = User::whereHas('etecs', function ($query) use ($etecIds) {
            $query->whereIn('etecs.id', $etecIds);
        })->get();

        return view('users.index', compact('usuarios'));
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
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'cpf' => ['required', 'digits:11', 'unique:users,cpf,'.$user->id],
            'phone' => ['required', 'regex:/^\d{2}9\d{8}$/'],
            'role' => ['required', 'in:aluno,professor,coordenador'],
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('status', 'Usuário atualizado!');
    }

    public function destroy(User $user)
    {
        if ($user->role === Role::Coordenador) {
            abort(403, 'Não é possível excluir um coordenador por aqui.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('status', 'Usuário removido!');
    }
}