<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\EtecContextController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::post('/trocar-etec', [EtecContextController::class, 'switch'])->name(
        'etec-context.switch',
    );

    Route::get('/visao-geral', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/senha', [PasswordController::class, 'update'])->name('password.update');

    Route::get('/turmas', [SchoolClassController::class, 'index'])->name('school-classes.index');

    Route::middleware('role:coordenador,professor')->group(function () {
        Route::get('/turmas/criar', [SchoolClassController::class, 'create'])->name(
            'school-classes.create',
        );
        Route::post('/turmas', [SchoolClassController::class, 'store'])->name(
            'school-classes.store',
        );
    });

    Route::get('/turmas/{schoolClass}', [SchoolClassController::class, 'show'])->name(
        'school-classes.show',
    );

    Route::middleware('role:coordenador,professor')->group(function () {
        Route::get('/turmas/{schoolClass}/editar', [SchoolClassController::class, 'edit'])->name(
            'school-classes.edit',
        );
        Route::put('/turmas/{schoolClass}', [SchoolClassController::class, 'update'])->name(
            'school-classes.update',
        );
        Route::delete('/turmas/{schoolClass}', [SchoolClassController::class, 'destroy'])->name(
            'school-classes.destroy',
        );
        Route::delete('/turmas/{schoolClass}/usuarios/{user}', [
            SchoolClassController::class,
            'removeUser',
        ])->name('school-classes.remove-user');
    });

    Route::middleware('role:coordenador')->group(function () {
        Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/usuarios', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/usuarios/{user}/editar', [UserManagementController::class, 'edit'])->name(
            'users.edit',
        );
        Route::get('/usuarios/filtrar', [UserManagementController::class, 'filtrar'])->name(
            'users.filtrar',
        );
        Route::put('/usuarios/{user}', [UserManagementController::class, 'update'])->name(
            'users.update',
        );
        Route::delete('/usuarios/{user}', [UserManagementController::class, 'destroy'])->name(
            'users.destroy',
        );
        Route::delete('/usuarios', [UserManagementController::class, 'destroyMultiple'])->name(
            'users.destroyMultiple',
        );
        Route::post('/usuarios/adicionar-turma', [
            UserManagementController::class,
            'addToClass',
        ])->name('users.add-to-class');
    });
});

require __DIR__ . '/auth.php';
