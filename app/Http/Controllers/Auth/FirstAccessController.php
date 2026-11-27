<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailVerificationService;
use App\Services\TrustedDeviceService;
use App\Services\UserIdentifierService;
use App\Support\EmailDomainValidator;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class FirstAccessController extends Controller
{
    public function __construct(
        private EmailVerificationService $verifications,
        private TrustedDeviceService $devices,
    ) {}

    public function email(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($redirect = $this->wrongStep($user, 'email_pendente')) {
            return $redirect;
        }

        $verification = $this->verifications->current($user, 'first_access');

        if ($verification && ! $verification->cancelled_at) {
            return view('auth.verify-code', [
                'title' => 'Confirme seu e-mail',
                'step' => 'Primeiro acesso: etapa 1 de 2',
                'intro' => "Enviamos um código de 6 dígitos para {$verification->email}. Digite-o abaixo para continuar.",
                'introNone' => '',
                'state' => 'active',
                'secondsLeft' => $this->verifications->secondsUntilResend($verification),
                'confirmRoute' => 'first-access.email.verify',
                'sendRoute' => 'first-access.email.resend',
                'changeRoute' => 'first-access.email.change',
            ]);
        }

        return view('auth.first-access-email', [
            'cancelled' => (bool) $verification?->cancelled_at,
        ]);
    }

    public function storeEmail(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($redirect = $this->wrongStep($user, 'email_pendente')) {
            return $redirect;
        }

        $request->merge(['email' => Str::lower(trim((string) $request->input('email')))]);

        $validated = $request->validate(
            [
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($user->id),
                    function ($attribute, $value, $fail) {
                        if (! EmailDomainValidator::isValid($value, Role::Aluno)) {
                            $fail('Use o seu e-mail institucional (@aluno.cps.sp.gov.br).');
                        }
                    },
                ],
            ],
            [
                'email.required' => 'Informe o seu e-mail.',
                'email.email' => 'Informe um e-mail válido.',
                'email.unique' => 'Este e-mail já está em uso por outra conta.',
            ],
        );

        $this->verifications->send($user, $validated['email'], 'first_access');

        return redirect()->route('first-access.email')
            ->with('status', 'Código enviado. Confira sua caixa de entrada.');
    }

    public function verifyEmail(Request $request, UserIdentifierService $identifierService): RedirectResponse
    {
        $user = $request->user();

        if ($redirect = $this->wrongStep($user, 'email_pendente')) {
            return $redirect;
        }

        $request->validate(
            ['code' => ['required', 'digits:6']],
            [
                'code.required' => 'Digite o código recebido por e-mail.',
                'code.digits' => 'O código tem 6 dígitos numéricos.',
            ],
        );

        $verification = $this->verifications->check($user, $request->input('code'), 'first_access');

        $name = $identifierService->generateName($verification->email);
        $firstName = $identifierService->extractFirstName($name);
        $identifier = $identifierService->generateIdentifier($firstName, $user->id);

        try {
            DB::transaction(function () use ($user, $verification, $name, $identifier) {
                $user->forceFill([
                    'name' => $name,
                    'identifier' => $identifier,
                    'email' => $verification->email,
                    'email_verified_at' => now(),
                    'first_access_status' => 'senha_pendente',
                ])->save();

                $verification->delete();
            });
        } catch (UniqueConstraintViolationException) {
            $verification->delete();

            return redirect()->route('first-access.email')->withErrors([
                'email' => 'Este e-mail já está em uso por outra conta.',
            ]);
        }

        $this->devices->trust($request, $user);
        $request->session()->put('device_verified', true);

        return redirect()->route('first-access.password');
    }

    public function resendEmail(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($redirect = $this->wrongStep($user, 'email_pendente')) {
            return $redirect;
        }

        $verification = $this->verifications->current($user, 'first_access');

        if ($verification) {
            $this->verifications->sendOrResend($user, $verification->email, 'first_access');
        }

        return redirect()->route('first-access.email')
            ->with('status', 'Código reenviado. Confira sua caixa de entrada.');
    }

    public function changeEmail(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($redirect = $this->wrongStep($user, 'email_pendente')) {
            return $redirect;
        }

        $this->verifications->current($user, 'first_access')?->delete();

        return redirect()->route('first-access.email');
    }

    public function password(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->wrongStep($request->user(), 'senha_pendente')) {
            return $redirect;
        }

        return view('auth.first-access-password');
    }

    public function storePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($redirect = $this->wrongStep($user, 'senha_pendente')) {
            return $redirect;
        }

        $request->validate(
            ['password' => ['required', 'confirmed', Rules\Password::defaults()]],
            [
                'password.required' => 'Informe a nova senha.',
                'password.confirmed' => 'As senhas não coincidem.',
            ],
        );

        if (Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Escolha uma senha diferente da senha inicial.',
            ]);
        }

        $user->forceFill([
            'password' => $request->input('password'),
            'first_access_status' => 'concluido',
        ])->save();

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    private function wrongStep(User $user, string $expected): ?RedirectResponse
    {
        if (! $user->isStudent()) {
            return redirect()->route('dashboard');
        }

        if ($user->first_access_status === $expected) {
            return null;
        }

        return match ($user->first_access_status) {
            'email_pendente' => redirect()->route('first-access.email'),
            'senha_pendente' => redirect()->route('first-access.password'),
            default => redirect()->route('dashboard'),
        };
    }
}
