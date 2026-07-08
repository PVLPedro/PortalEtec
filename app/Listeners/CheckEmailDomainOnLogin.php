<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;

class CheckEmailDomainOnLogin
{
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        if (!$user->hasValidEmailDomain()) {
            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();

            abort(403, 'O domínio de email não condiz com o cargo registrado. Por favor, entre em contato com a coordenação da escola.');
        }
    }
}