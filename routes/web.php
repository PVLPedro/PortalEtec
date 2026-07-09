<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\Auth\PasswordController;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('register');
    }

    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/visao-geral', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/senha', [PasswordController::class, 'update'])->name('password.update');

    Route::middleware('role:coordenador')->group(function () {
        Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/usuarios', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/usuarios/{user}/editar', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/usuarios/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/usuarios/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });
});

require __DIR__.'/auth.php';