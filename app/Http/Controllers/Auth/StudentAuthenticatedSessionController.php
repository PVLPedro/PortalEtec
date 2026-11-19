<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StudentLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentAuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login-student');
    }

    public function store(StudentLoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
