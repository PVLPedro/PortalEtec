<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Etec;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $etec = Etec::where('code', 6)->firstOrFail();

        $students = [
            ['name' => 'Yuji', 'rm' => 1234567],
            ['name' => 'samuelgato', 'rm' => 1234568],
        ];

        foreach ($students as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => null,
                'password' => 'password',
                'initial_password' => 'password',
                'role' => Role::Aluno,
            ]);

            $user->etecs()->attach($etec->id, [
                'rm' => $data['rm'],
                'role' => Role::Aluno->value,
            ]);

            $user->student()->create(['rm' => $data['rm']]);
        }

        $siles = User::create([
            'name' => 'Siles',
            'email' => 'siles@cps.sp.gov.br',
            'password' => 'password4',
            'role' => Role::Professor,
        ]);
        $siles->etecs()->attach($etec->id, ['role' => Role::Professor->value]);
        $siles->teacher()->create();

        $coordinators = [
            ['name' => 'Davi', 'email' => 'davi@cps.sp.gov.br', 'password' => 'password3'],
            ['name' => 'Afonso', 'email' => 'afonso@cps.sp.gov.br', 'password' => 'password3'],
            ['name' => 'furigo', 'email' => 'furigo@cps.sp.gov.br', 'password' => 'password67'],
        ];

        foreach ($coordinators as $data) {
            $user = User::create([...$data, 'role' => Role::Coordenador]);
            $user->etecs()->attach($etec->id, ['role' => Role::Coordenador->value]);
            $user->coordinator()->create();
        }
    }
}
