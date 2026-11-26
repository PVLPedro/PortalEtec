<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

trait ConfirmsPassword
{
    protected function confirmPassword(Request $request): void
    {
        $request->validate(
            ['password' => ['required', 'string']],
            ['password.required' => 'Confirme sua senha para continuar.'],
        );

        if (! Hash::check($request->input('password'), $request->user()->password)) {
            throw ValidationException::withMessages(['password' => 'Senha incorreta.']);
        }
    }
}
