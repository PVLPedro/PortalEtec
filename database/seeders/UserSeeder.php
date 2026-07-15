<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\Role;
use App\Models\Etec;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $etec = Etec::first();

        User::factory()
            ->create([
                'name' => 'Yuji',
                'email' => 'yuji@aluno.cps.sp.gov.br',
                'password' => 'password',
                'role' => Role::Aluno,
            ])
            ->etecs()
            ->attach($etec->id, ['rm' => '1234567']);

        User::factory()
            ->create([
                'name' => 'samuelgato',
                'email' => 'samuelgato@aluno.cps.sp.gov.br',
                'password' => 'password',
                'role' => Role::Aluno,
            ])
            ->etecs()
            ->attach($etec->id, ['rm' => '1234568']);

        User::factory()
            ->create([
                'name' => 'Siles',
                'email' => 'siles@cps.sp.gov.br',
                'password' => 'password4',
                'role' => Role::Professor,
            ])
            ->etecs()
            ->attach($etec->id);

        User::factory()
            ->create([
                'name' => 'Davi',
                'email' => 'davi@cps.sp.gov.br',
                'password' => 'password3',
                'role' => Role::Coordenador,
            ])
            ->etecs()
            ->attach($etec->id);
    }
}
