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
            'password' => bcrypt('password'),
            'role' => Role::Aluno,
        ]);

        User::factory()->create([
            'name' => 'Siles',
            'email' => 'siles@gmail.com',
            'password' => bcrypt('password2'),
            'role' => Role::Professor,
        ]);

        User::factory()->create([
            'name' => 'Davi',
            'email' => 'davi@gmail.com',
            'password' => bcrypt('password3'),
            'role' => Role::Coordenador,
        ]);
    }
}