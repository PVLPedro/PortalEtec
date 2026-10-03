<?php

namespace App\Http\Requests\Auth;

use App\Models\Etec;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StudentLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rm' => ['required', 'digits:7'],
            'etec_code' => ['required', 'integer'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = $this->findStudent();

        if (!$user || !Hash::check($this->input('password'), $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'rm' => trans('auth.failed'),
            ]);
        }

        Auth::login($user, $this->boolean('remember'));
        RateLimiter::clear($this->throttleKey());
    }

    private function findStudent(): ?User
    {
        $etec = Etec::where('codigo', $this->input('etec_code'))->first();

        if (!$etec) {
            return null;
        }

        $worker = DB::table('etec_worker')
            ->where('id_etec', $etec->id)
            ->where('rm', $this->input('rm'))
            ->where('role', 'aluno')
            ->first();

        return $worker ? User::find($worker->user_id) : null;
    }

    private function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'rm' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    private function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->input('etec_code') . '|' . $this->input('rm')) . '|' . $this->ip(),
        );
    }
}
