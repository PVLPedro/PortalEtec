<?php

namespace App\Services;

use App\Mail\VerificationCodeMail;
use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Throwable;

class EmailVerificationService
{
    public const TTL_MINUTES = 10;
    public const RESEND_SECONDS = 60;
    public const MAX_ATTEMPTS = 5;

    public function current(User $user, string $purpose): ?EmailVerification
    {
        return EmailVerification::where('user_id', $user->id)
            ->where('purpose', $purpose)
            ->first();
    }

    public function secondsUntilResend(?EmailVerification $verification): int
    {
        if (!$verification) {
            return 0;
        }

        return max(0, self::RESEND_SECONDS - (now()->timestamp - $verification->sent_at->timestamp));
    }

    public function send(User $user, string $email, string $purpose): EmailVerification
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $verification = EmailVerification::updateOrCreate(
            ['user_id' => $user->id],
            [
                'email' => $email,
                'purpose' => $purpose,
                'code' => Hash::make($code),
                'attempts' => 0,
                'sent_at' => now(),
                'expires_at' => now()->addMinutes(self::TTL_MINUTES),
                'cancelled_at' => null,
            ],
        );

        try {
            Mail::to($email)->send(
                new VerificationCodeMail($user, $code, $purpose, $this->cancelUrl($verification)),
            );
        } catch (Throwable $e) {
            report($e);

            throw ValidationException::withMessages([
                'code' => 'Não foi possível enviar o e-mail agora. Tente novamente em instantes.',
            ]);
        }

        return $verification;
    }

    public function sendOrResend(User $user, string $email, string $purpose): EmailVerification
    {
        $current = $this->current($user, $purpose);

        if ($current?->cancelled_at) {
            throw ValidationException::withMessages([
                'code' => 'Esta tentativa foi cancelada. Faça login novamente para receber um novo código.',
            ]);
        }

        $wait = $this->secondsUntilResend($current);

        if ($wait > 0) {
            throw ValidationException::withMessages([
                'code' => "Aguarde {$wait} segundos para reenviar o código.",
            ]);
        }

        return $this->send($user, $email, $purpose);
    }

    public function check(User $user, string $code, string $purpose): EmailVerification
    {
        $verification = $this->current($user, $purpose);

        if (!$verification || $verification->cancelled_at) {
            $this->fail('Esta verificação não está mais ativa. Peça um novo código.');
        }

        if ($verification->expires_at->isPast()) {
            $this->fail('O código expirou. Envie um novo código.');
        }

        if ($verification->attempts >= self::MAX_ATTEMPTS) {
            $this->fail('Muitas tentativas incorretas. Envie um novo código.');
        }

        if (!Hash::check($code, $verification->code)) {
            $verification->increment('attempts');
            $this->fail('Código incorreto.');
        }

        return $verification;
    }

    private function cancelUrl(EmailVerification $verification): string
    {
        return URL::temporarySignedRoute('verification.cancel', now()->addDays(2), [
            'verification' => $verification->id,
        ]);
    }

    private function fail(string $message): never
    {
        throw ValidationException::withMessages(['code' => $message]);
    }
}
