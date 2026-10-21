<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Etec;
use App\Enums\Role;
use App\Support\EmailDomainValidator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register', [
            'etecs' => Etec::orderBy('nome')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
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
                    $role = Role::tryFrom((string) $request->input('role'));

                    if (!$role || !EmailDomainValidator::isValid($value, $role)) {
                        $fail('O domínio do email não corresponde ao tipo de usuário selecionado.');
                    }
                },
            ],
            'role' => ['required', 'in:professor,coordenador'],
            'etecs' => ['required', 'array', 'min:1'],
            'etecs.*' => ['exists:etecs,id'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $role = Role::from($validated['role']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $role,
            'password' => $validated['password'],
        ]);

        $user
            ->etecs()
            ->sync(
                collect($validated['etecs'])->mapWithKeys(
                    fn($etecId) => [$etecId => ['role' => $role->value]],
                ),
            );

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
