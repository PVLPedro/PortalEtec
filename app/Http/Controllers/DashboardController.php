<?php

namespace App\Http\Controllers;

use App\Enums\Role;

class DashboardController extends Controller
{
    public function index()
    {
        return match (auth()->user()->role) {
            Role::Aluno => view('aluno.dashboard'),
            Role::Professor => view('professor.dashboard'),
            Role::Coordenador => view('coordenador.dashboard'),
        };
    }
}