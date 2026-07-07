<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Enums\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Yuji',
            'email' => 'yuji@gmail.com',
            'cpf' => '11111111111',
            'phone' => '11987654321',
            'password' => bcrypt('password'),
            'role' => Role::Aluno,
        ]);

        User::factory()->create([
            'name' => 'Siles',
            'email' => 'siles@gmail.com',
            'cpf' => '22222222222',
            'phone' => '11987654322',
            'password' => bcrypt('password2'),
            'role' => Role::Professor,
        ]);

        User::factory()->create([
            'name' => 'Davi',
            'email' => 'davi@gmail.com',
            'cpf' => '33333333333',
            'phone' => '11987654323',
            'password' => bcrypt('password3'),
            'role' => Role::Coordenador,
        ]);
    }
}