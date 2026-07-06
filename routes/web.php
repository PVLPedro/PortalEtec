<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return view('home');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Tu vai adicionar as rotas de autenticação aqui

// Route::middleware(['auth', 'role:coordenador'])->group(function () {
//     Route::get('/coordenador/dashboard', ...);
//     Route::post('/professores/delegar', ...);
// });

// Route::middleware(['auth', 'role:professor,coordenador'])->group(function () {
//     Route::get('/turmas', ...);
// });

Route::middleware(['auth', 'role:aluno'])->get('/aluno/dashboard', function () {
    return view('aluno.dashboard');
})->name('aluno.dashboard');

Route::middleware(['auth', 'role:professor'])->get('/professor/dashboard', function () {
    return view('professor.dashboard');
})->name('professor.dashboard');

Route::middleware(['auth', 'role:coordenador'])->get('/coordenador/dashboard', function () {
    return view('coordenador.dashboard');
})->name('coordenador.dashboard');

require __DIR__.'/auth.php';
