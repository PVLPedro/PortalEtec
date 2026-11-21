<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\SchoolClassDisciplineController;
use Illuminate\Support\Facades\Route;

Route::get('/turma/{schoolClass}/disciplinas', [SchoolClassDisciplineController::class, 'index'])->name(
    'class.disciplines.index',
);
Route::get('/turma/{schoolClass}/comunicados', [AnnouncementController::class, 'index'])->name(
    'class.announcements.index',
);
Route::get('/turma/{schoolClass}/atividades', [ActivityController::class, 'index'])->name(
    'class.activities.index',
);
Route::get('/atividades/arquivos/{file}', [ActivityController::class, 'download'])->name(
    'class.activities.files.download',
);

Route::middleware('role:coordenador')->group(function () {
    Route::get('/turma/{schoolClass}/disciplinas/criar', [
        SchoolClassDisciplineController::class,
        'create',
    ])->name('class.disciplines.create');
    Route::post('/turma/{schoolClass}/disciplinas', [
        SchoolClassDisciplineController::class,
        'store',
    ])->name('class.disciplines.store');
    Route::get('/turma/{schoolClass}/disciplinas/{classDiscipline}/editar', [
        SchoolClassDisciplineController::class,
        'edit',
    ])->name('class.disciplines.edit');
    Route::put('/turma/{schoolClass}/disciplinas/{classDiscipline}', [
        SchoolClassDisciplineController::class,
        'update',
    ])->name('class.disciplines.update');
    Route::delete('/turma/{schoolClass}/disciplinas/{classDiscipline}', [
        SchoolClassDisciplineController::class,
        'destroy',
    ])->name('class.disciplines.destroy');
});

Route::middleware('role:coordenador,professor')->group(function () {
    Route::get('/turma/{schoolClass}/comunicados/criar', [AnnouncementController::class, 'create'])->name(
        'class.announcements.create',
    );
    Route::post('/turma/{schoolClass}/comunicados', [AnnouncementController::class, 'store'])->name(
        'class.announcements.store',
    );
    Route::get('/turma/{schoolClass}/comunicados/{announcement}/editar', [
        AnnouncementController::class,
        'edit',
    ])->name('class.announcements.edit');
    Route::put('/turma/{schoolClass}/comunicados/{announcement}', [
        AnnouncementController::class,
        'update',
    ])->name('class.announcements.update');
    Route::delete('/turma/{schoolClass}/comunicados/{announcement}', [
        AnnouncementController::class,
        'destroy',
    ])->name('class.announcements.destroy');

    Route::get('/turma/{schoolClass}/atividades/criar', [ActivityController::class, 'create'])->name(
        'class.activities.create',
    );
    Route::post('/turma/{schoolClass}/atividades', [ActivityController::class, 'store'])->name(
        'class.activities.store',
    );
    Route::get('/turma/{schoolClass}/atividades/{activity}/editar', [
        ActivityController::class,
        'edit',
    ])->name('class.activities.edit');
    Route::put('/turma/{schoolClass}/atividades/{activity}', [
        ActivityController::class,
        'update',
    ])->name('class.activities.update');
    Route::delete('/turma/{schoolClass}/atividades/{activity}', [
        ActivityController::class,
        'destroy',
    ])->name('class.activities.destroy');
});

Route::get('/turma/{schoolClass}/atividades/{activity}', [ActivityController::class, 'show'])->name(
    'class.activities.show',
);
