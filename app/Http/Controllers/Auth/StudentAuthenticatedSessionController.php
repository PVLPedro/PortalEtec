<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StudentLoginRequest;
use App\Services\TrustedDeviceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentAuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login-student');
    }

    public function store(StudentLoginRequest $request, TrustedDeviceService $devices): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $devices->startSession($request, $request->user());

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
