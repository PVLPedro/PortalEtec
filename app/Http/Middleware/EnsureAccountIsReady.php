<?php

namespace App\Http\Middleware;

use App\Services\TrustedDeviceService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsReady
{
    public function __construct(private TrustedDeviceService $devices) {}

    public function handle(Request $request, Closure $next, ?string $mode = null): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if (
            $this->devices->required($user) &&
            $request->session()->get('device_verified') !== true
        ) {
            return redirect()->route('device.verify');
        }

        if ($mode === 'device') {
            return $next($request);
        }

        if ($user->isStudent()) {
            return match ($user->first_access_status) {
                'email_pendente' => redirect()->route('first-access.email'),
                'senha_pendente' => redirect()->route('first-access.password'),
                default => $next($request),
            };
        }

        if ($user->email_verified_at === null) {
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
