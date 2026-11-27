<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Etec;
use App\Models\User;
use App\Services\EmailVerificationService;
use App\Services\UserIdentifierService;
use App\Support\EmailDomainValidator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register', [
            'etecs' => Etec::orderBy('name')->get(),
        ]);
    }

    public function store(
        Request $request,
        EmailVerificationService $verifications,
        UserIdentifierService $identifierService,
    ): RedirectResponse {
        // Monta o e-mail completo a partir do prefixo digitado
        $request->merge([
            'email' => strtolower(trim((string) $request->input('email_prefix'))).'@cps.sp.gov.br',
        ]);

        $validated = $request->validate([
            'email_prefix' => ['required', 'string', 'max:200'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:'.User::class,
                function ($attribute, $value, $fail) use ($request) {
                    $role = Role::tryFrom((string) $request->input('role'));

                    if (! $role || ! EmailDomainValidator::isValid($value, $role)) {
                        $fail('O domínio do email não corresponde ao tipo de usuário selecionado.');
                    }
                },
            ],
            'role' => ['required', 'in:professor,coordenador'],
            'etecs' => array_filter([
                'nullable',
                'array',
                $request->input('role') === 'coordenador' ? 'min:1' : null,
            ]),
            'etecs.*' => ['exists:etecs,id'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $role = Role::from($validated['role']);

        $name = $identifierService->generateName($validated['email']);
        $firstName = $identifierService->extractFirstName($name);
        $identifier = $identifierService->generateIdentifier($firstName);

        $user = DB::transaction(function () use ($validated, $role, $name, $identifier) {
            $user = User::create([
                'name' => $name,
                'identifier' => $identifier,
                'email' => $validated['email'],
                'role' => $role,
                'password' => $validated['password'],
            ]);

            $user
                ->etecs()
                ->sync(
                    collect($validated['etecs'] ?? [])->mapWithKeys(
                        fn ($etecId) => [$etecId => ['role' => $role->value]],
                    ),
                );

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        try {
            $verifications->send($user, $user->email, 'register');
        } catch (ValidationException) {
        }

        return redirect()->route('verification.notice');
    }
}
