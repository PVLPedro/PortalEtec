<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\FirstAccessController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerificationCancelController;
use App\Http\Controllers\Auth\VerificationCodeController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StudentAuthenticatedSessionController;

Route::middleware('guest')->group(function () {
    Route::get('cadastrar', [RegisteredUserController::class, 'create'])->name('register');

    Route::post('cadastrar', [RegisteredUserController::class, 'store']);

    Route::get('entrar', [AuthenticatedSessionController::class, 'create'])->name('login');

    Route::post('entrar', [AuthenticatedSessionController::class, 'store']);

    Route::get('entrar/aluno', [StudentAuthenticatedSessionController::class, 'create'])->name(
        'login.student',
    );

    Route::post('entrar/aluno', [StudentAuthenticatedSessionController::class, 'store']);

    Route::get('esqueci-senha', [PasswordResetLinkController::class, 'create'])->name(
        'password.request',
    );

    Route::post('esqueci-senha', [PasswordResetLinkController::class, 'store'])->name(
        'password.email',
    );

    Route::get('resetar-senha/{token}', [NewPasswordController::class, 'create'])->name(
        'password.reset',
    );

    Route::post('resetar-senha', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('verificar-email', [VerificationCodeController::class, 'show'])
        ->defaults('purpose', 'register')
        ->name('verification.notice');

    Route::post('verificar-email/enviar', [VerificationCodeController::class, 'send'])
        ->defaults('purpose', 'register')
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::post('verificar-email', [VerificationCodeController::class, 'verify'])
        ->defaults('purpose', 'register')
        ->middleware('throttle:10,1')
        ->name('verification.verify');

    Route::get('verificar-dispositivo', [VerificationCodeController::class, 'show'])
        ->defaults('purpose', 'new_device')
        ->name('device.verify');

    Route::post('verificar-dispositivo/enviar', [VerificationCodeController::class, 'send'])
        ->defaults('purpose', 'new_device')
        ->middleware('throttle:6,1')
        ->name('device.send');

    Route::post('verificar-dispositivo', [VerificationCodeController::class, 'verify'])
        ->defaults('purpose', 'new_device')
        ->middleware('throttle:10,1')
        ->name('device.confirm');

    Route::get('primeiro-acesso/email', [FirstAccessController::class, 'email'])->name(
        'first-access.email',
    );

    Route::post('primeiro-acesso/email', [FirstAccessController::class, 'storeEmail'])
        ->middleware('throttle:6,1')
        ->name('first-access.email.store');

    Route::post('primeiro-acesso/email/codigo', [FirstAccessController::class, 'verifyEmail'])
        ->middleware('throttle:10,1')
        ->name('first-access.email.verify');

    Route::post('primeiro-acesso/email/reenviar', [FirstAccessController::class, 'resendEmail'])
        ->middleware('throttle:6,1')
        ->name('first-access.email.resend');

    Route::post('primeiro-acesso/email/trocar', [FirstAccessController::class, 'changeEmail'])->name(
        'first-access.email.change',
    );

    Route::get('primeiro-acesso/senha', [FirstAccessController::class, 'password'])
        ->middleware('account.ready:device')
        ->name('first-access.password');

    Route::post('primeiro-acesso/senha', [FirstAccessController::class, 'storePassword'])
        ->middleware('account.ready:device')
        ->name('first-access.password.store');

    Route::get('confirmar-senha', [ConfirmablePasswordController::class, 'show'])->name(
        'password.confirm',
    );

    Route::post('confirmar-senha', [ConfirmablePasswordController::class, 'store']);

    Route::put('senha', [PasswordController::class, 'update'])->name('password.update');

    Route::post('sair', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::get('verificacao/{verification}/cancelar', VerificationCancelController::class)
    ->middleware(['signed', 'throttle:20,1'])
    ->name('verification.cancel');
