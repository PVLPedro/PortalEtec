<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Etec;
use App\Enums\Role;
use App\Support\EmailDomainValidator;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Mostra a view de cadastro.
     */
    public function create(): View
    {
        return view('auth.register', [
            'etecs' => Etec::orderBy('nome')->get(),
        ]);
    }

    /**
     * Cuida da validação de cadastro do usuário.
     * @throws ValidationException
     */

    public function store(Request $request): RedirectResponse
    {
        $isAluno = $request->input('role') === Role::Aluno->value;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
                function ($attribute, $value, $fail) use ($request) {
                    $role = Role::from($request->input('role'));
                    if (!EmailDomainValidator::isValid($value, $role)) {
                        $fail('O domínio do email não corresponde ao tipo de usuário selecionado.');
                    }
                },
            ],
            'role' => ['required', 'in:aluno,professor,coordenador'],
            'etecs' => ['required', 'array', $isAluno ? 'size:1' : 'min:1'],
            'etecs.*' => ['exists:etecs,id'],
            'rm' => [
                Rule::requiredIf($isAluno),
                'nullable',
                'digits:7',
                Rule::unique('etec_user', 'rm')->where('etec_id', $request->input('etecs.0')),
            ],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => Role::from($validated['role']),
            'password' => Hash::make($validated['password']),
        ]);

        $user->etecs()->sync(
            collect($validated['etecs'])->mapWithKeys(
                fn($etecId) => [
                    $etecId => ['rm' => $validated['rm'] ?? null],
                ],
            ),
        );

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
