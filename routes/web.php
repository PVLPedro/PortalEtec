<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    } else {
        $role = auth()->user()->role;

        switch ($role) {
            case \App\Enums\Role::Aluno:
                return redirect()->route('aluno.dashboard');
            case \App\Enums\Role::Professor:
                return redirect()->route('professor.dashboard');
            case \App\Enums\Role::Coordenador:
                return redirect()->route('coordenador.dashboard');
            default:
                abort(403);
        }
    }
});

Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:aluno'])->get('/aluno/visao-geral', function () {
    return view('aluno.dashboard');
})->name('aluno.dashboard');

Route::middleware(['auth', 'role:professor'])->get('/professor/visao-geral', function () {
    return view('professor.dashboard');
})->name('professor.dashboard');

Route::middleware(['auth', 'role:coordenador'])->get('/coordenador/visao-geral', function () {
    return view('coordenador.dashboard');
})->name('coordenador.dashboard');

// Tu vai adicionar as rotas de autenticação aqui

// Ex.:
// Route::middleware(['auth', 'role:coordenador'])->group(function () {
//     Route::get('/coordenador/dashboard', ...);
//     Route::post('/professores/delegar', ...);
// });

// Route::middleware(['auth', 'role:professor,coordenador'])->group(function () {
//     Route::get('/turmas', ...);
// });

require __DIR__.'/auth.php';
