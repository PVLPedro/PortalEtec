<?php

namespace App\Http\Controllers;

use App\Enums\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $activeEtec = $user->activeEtec();

        return match ($user->role) {
            Role::Aluno => view('aluno.dashboard', compact('activeEtec')),
            Role::Professor => view('professor.dashboard', [
                'activeEtec' => $activeEtec,
                'schoolClasses' => $activeEtec->schoolClasses,
            ]),
            Role::Coordenador => view('coordenador.dashboard', [
                'activeEtec' => $activeEtec,
                'schoolClasses' => $activeEtec->schoolClasses,
            ]),
        };
    }
}
