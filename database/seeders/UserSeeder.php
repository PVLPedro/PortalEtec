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
            'cpf' => '12345678901',
            'phone' => '11999999999',
            'password' => 'password',
            'role' => Role::Aluno,
        ]);

        User::factory()->create([
            'name' => 'samuelgato',
            'email' => 'samuelgato@aluno.cps.sp.gov.br',
            'cpf' => '12345678902',
            'phone' => '11999999998',
            'password' => 'password',
            'role' => Role::Aluno,
        ]);

        User::factory()->create([
            'name' => 'Siles',
            'email' => 'siles@cps.gov.br',
            'cpf' => '12345678903',
            'phone' => '11999999997',
            'password' => 'password4',
            'role' => Role::Professor,
        ]);

        User::factory()->create([
            'name' => 'Davi',
            'email' => 'davi@cps.gov.br',
            'cpf' => '12345678904',
            'phone' => '11999999996',
            'password' => 'password3',
            'role' => Role::Coordenador,
        ]);

        User::factory()->create([
            'name' => 'Pedro',
            'email' => 'pvlprofissional@gmail.com',
            'password' => bcrypt('password4'),
            'role' => Role::Coordenador,
        ]);
    }
}