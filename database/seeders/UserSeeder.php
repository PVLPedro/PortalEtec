<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Conta de aluno (Yuji) - email com domínio válido para alunos
        User::factory()->create([
            'name' => 'Yuji',
            'email' => 'yuji@aluno.cps.sp.gov.br',
            'password' => 'password',
            'role' => Role::Aluno,
        ]);

        // Conta de aluno (samuelgato) - email com domínio válido para alunos
        User::factory()->create([
            'name' => 'samuelgato',
            'email' => 'samuelgato@aluno.cps.sp.gov.br',
            'password' => 'password',
            'role' => Role::Aluno,
        ]);

        // Conta de professor (Siles) - email com domínio válido para professores
        User::factory()->create([
            'name' => 'Siles',
            'email' => 'siles@cps.gov.br',
            'password' => 'password4',
            'role' => Role::Professor,
        ]);

        // Conta de coordenador (Davi) - email com domínio válido para coordenadores
        User::factory()->create([
            'name' => 'Davi',
            'email' => 'davi@cps.gov.br',
            'password' => 'password3',
            'role' => Role::Coordenador,
        ]);
    }
}