<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\EmailVerificationService;
use App\Services\TrustedDeviceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationCodeController extends Controller
{
    private const PAGES = [
        'register' => [
            'title' => 'Confirme seu e-mail',
            'intro' => 'Enviamos um código de 6 dígitos para :email. Digite-o abaixo para liberar sua conta.',
            'none' => 'Para liberar sua conta, enviaremos um código de 6 dígitos para :email.',
            'confirm' => 'verification.verify',
            'send' => 'verification.send',
        ],
        'new_device' => [
            'title' => 'Confirme este dispositivo',
            'intro' => 'Detectamos um acesso à sua conta a partir de um dispositivo novo. Enviamos um código de 6 dígitos para :email.',
            'none' => 'Detectamos um acesso a partir de um dispositivo novo. Enviaremos um código de 6 dígitos para :email.',
            'confirm' => 'device.confirm',
            'send' => 'device.send',
        ],
    ];

    public function __construct(
        private EmailVerificationService $verifications,
        private TrustedDeviceService $devices,
    ) {}

    public function show(Request $request, string $purpose): View|RedirectResponse
    {
        $user = $request->user();

        if (($purpose === 'register' && $user->isStudent()) || $this->done($request, $purpose)) {
            return redirect()->route('dashboard');
        }

        $page = self::PAGES[$purpose];
        $verification = $this->verifications->current($user, $purpose);
        $email = $verification?->email ?? $user->email;

        return view('auth.verify-code', [
            'title' => $page['title'],
            'intro' => str_replace(':email', $email, $page['intro']),
            'introNone' => str_replace(':email', $email, $page['none']),
            'state' => ! $verification ? 'none' : ($verification->cancelled_at ? 'cancelled' : 'active'),
            'secondsLeft' => $this->verifications->secondsUntilResend($verification),
            'confirmRoute' => $page['confirm'],
            'sendRoute' => $page['send'],
            'changeRoute' => null,
            'step' => null,
        ]);
    }

    public function send(Request $request, string $purpose): RedirectResponse
    {
        $user = $request->user();

        $this->verifications->sendOrResend($user, $user->email, $purpose);

        return redirect()->route($purpose === 'register' ? 'verification.notice' : 'device.verify')
            ->with('status', 'Código enviado. Confira sua caixa de entrada.');
    }

    public function verify(Request $request, string $purpose): RedirectResponse
    {
        $request->validate(
            ['code' => ['required', 'digits:6']],
            [
                'code.required' => 'Digite o código recebido por e-mail.',
                'code.digits' => 'O código tem 6 dígitos numéricos.',
            ],
        );

        $user = $request->user();

        $verification = $this->verifications->check($user, $request->input('code'), $purpose);

        if ($purpose === 'register') {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        $verification->delete();

        $this->devices->trust($request, $user);
        $request->session()->put('device_verified', true);

        return redirect()->route('dashboard');
    }

    private function done(Request $request, string $purpose): bool
    {
        $user = $request->user();

        if ($purpose === 'register') {
            return $user->email_verified_at !== null;
        }

        return ! $this->devices->required($user) ||
            $request->session()->get('device_verified') === true;
    }
}
