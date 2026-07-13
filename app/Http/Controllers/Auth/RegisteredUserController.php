<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Etec;
use App\Enums\Role;
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
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'etecs' => Etec::orderBy('nome')->get(),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
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
                new ValidEmailDomainForRole($request->role),
            ],
            'cpf' => ['required', 'digits:11', 'unique:' . User::class],
            'phone' => ['required', 'regex:/^\d{2}9\d{8}$/'],
            'role' => ['required', 'in:aluno,professor,coordenador'],
            'etec_id' => ['required', 'exists:etecs,id'],
            'rm' => [
                Rule::requiredIf($request->role === 'aluno'),
                'nullable',
                'digits:7',
                Rule::unique('etec_user', 'rm')->where('etec_id', $request->etec_id),
            ],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cpf' => $validated['cpf'],
            'phone' => $validated['phone'],
            'role' => Role::from($validated['role']),
            'password' => Hash::make($validated['password']),
        ]);

        $user->etecs()->attach($validated['etec_id'], ['rm' => $validated['rm'] ?? null]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
