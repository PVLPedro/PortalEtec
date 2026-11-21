<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TrustedDeviceService
{
    public const COOKIE = 'portal_device';

    public function __construct(private EmailVerificationService $verifications) {}

    public function required(User $user): bool
    {
        return config('portal.device_check') &&
            $user->email !== null &&
            $user->email_verified_at !== null;
    }

    public function isTrusted(Request $request, User $user): bool
    {
        $token = $request->cookie(self::COOKIE);

        if (!is_string($token) || $token === '') {
            return false;
        }

        $device = $user->trustedDevices()->where('token_hash', hash('sha256', $token))->first();

        if (!$device) {
            return false;
        }

        $device->update([
            'last_used_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
        ]);

        return true;
    }

    public function trust(Request $request, User $user): void
    {
        $token = $request->cookie(self::COOKIE);

        if (!is_string($token) || $token === '') {
            $token = Str::random(64);
        }

        $user->trustedDevices()->updateOrCreate(
            ['token_hash' => hash('sha256', $token)],
            [
                'last_used_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
            ],
        );

        Cookie::queue(
            Cookie::make(self::COOKIE, $token, 60 * 24 * 730, httpOnly: true, sameSite: 'lax'),
        );
    }

    public function startSession(Request $request, User $user): void
    {
        if (!$this->required($user) || $this->isTrusted($request, $user)) {
            $request->session()->put('device_verified', true);

            return;
        }

        $request->session()->put('device_verified', false);

        try {
            $this->verifications->send($user, $user->email, 'new_device');
        } catch (ValidationException) {
        }
    }
}
