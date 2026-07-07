<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        return view('coordenador.usuarios.index', [
            'usuarios' => User::all(),
        ]);
    }

    public function edit(User $user)
    {
        return view('coordenador.usuarios.edit', compact('user'));
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

        return redirect()->route('usuarios.index')->with('status', 'Usuário atualizado!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('usuarios.index')->with('status', 'Usuário removido!');
    }
}