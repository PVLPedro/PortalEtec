<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use App\Rules\ValidEmailDomainForRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    public function create()
    {
        return view('coordenador.usuarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:aluno,professor,coordenador'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:'.User::class,
                new ValidEmailDomainForRole($request->role),
            ],
            'cpf' => ['required', 'digits:11', 'unique:'.User::class],
            'phone' => ['required', 'regex:/^\d{2}9\d{8}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cpf' => $validated['cpf'],
            'phone' => $validated['phone'],
            'role' => Role::from($validated['role']),
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('coordenador.dashboard')->with('status', 'Usuário criado com sucesso!');
    }
}