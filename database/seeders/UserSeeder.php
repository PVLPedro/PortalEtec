<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Yuji',
            'email' => 'yuji@aluno.cps.sp.gov.br',
            'password' => 'password',
            'role' => Role::Aluno,
        ]);

        User::factory()->create([
            'name' => 'samuelgato',
            'email' => 'samuelgato@aluno.cps.sp.gov.br',
            'password' => 'password',
            'role' => Role::Aluno,
        ]);

        User::factory()->create([
            'name' => 'Siles',
            'email' => 'siles@cps.gov.br',
            'password' => 'password4',
            'role' => Role::Professor,
        ]);

        User::factory()->create([
            'name' => 'Davi',
            'email' => 'davi@cps.gov.br',
            'password' => 'password3',
            'role' => Role::Coordenador,
        ]);
    }
}