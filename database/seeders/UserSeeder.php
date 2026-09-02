<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\Role;
use App\Models\Etec;
use App\Models\UserStudent;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $etec = Etec::first();
        $firstEtec = Etec::first();
        $camargoEtec = Etec::where('code', 12)->first();
        $belemEtec = Etec::where('code', 220)->first();

        User::factory()
            ->create([
                'name' => 'Pedro',
                'email' => 'pedro@aluno.cps.sp.gov.br',
                'password' => 'password',
                'role' => Role::Aluno,
            ])
            ->etecs()
            ->attach($camargoEtec->id, ['rm' => '1111111']);

        User::factory()
            ->create([
                'name' => 'Davi',
                'email' => 'davi@aluno.cps.sp.gov.br',
                'password' => 'password',
                'role' => Role::Aluno,
            ])
            ->etecs()
            ->attach($camargoEtec->id, ['rm' => '2222222']);

        User::factory()
            ->create([
                'name' => 'Gustavo Lopez',
                'email' => 'gustavo.lopez@aluno.cps.sp.gov.br',
                'password' => 'password',
                'role' => Role::Aluno,
            ])
            ->etecs()
            ->attach($camargoEtec->id, ['rm' => '3333333']);

        $yuji = User::factory()->create([
            'name' => 'Yuji',
            'email' => 'yuji@aluno.cps.sp.gov.br',
            'password' => 'password',
            'role' => Role::Aluno,
        ]);
        $yuji->etecs()->attach($etec->id, ['role' => Role::Aluno->value]);
        UserStudent::create(['user_id' => $yuji->id, 'rm' => 1234567]);

        $samuel = User::factory()->create([
            'name' => 'samuelgato',
            'email' => 'samuelgato@aluno.cps.sp.gov.br',
            'password' => 'password',
            'role' => Role::Aluno,
        ]);
        $samuel->etecs()->attach($etec->id, ['role' => Role::Aluno->value]);
        UserStudent::create(['user_id' => $samuel->id, 'rm' => 1234568]);

        User::factory()
            ->create([
                'name' => 'Siles',
                'email' => 'siles@cps.sp.gov.br',
                'password' => 'password4',
                'role' => Role::Professor,
            ])
            ->etecs()
            ->attach($etec->id, ['role' => Role::Professor->value]);

        User::factory()
            ->create([
                'name' => 'Davi',
                'email' => 'davi@cps.sp.gov.br',
                'password' => 'password3',
                'role' => Role::Coordenador,
            ])
            ->etecs()
            ->attach($etec->id, ['role' => Role::Coordenador->value]);

        User::factory()
            ->create([
                'name' => 'Afonso',
                'email' => 'afonso@cps.sp.gov.br',
                'password' => 'password3',
                'role' => Role::Coordenador,
            ])
            ->etecs()
            ->attach($etec->id, ['role' => Role::Coordenador->value]);

        User::factory()
            ->create([
                'name' => 'furigo',
                'email' => 'furigo@cps.sp.gov.br',
                'password' => 'password67',
                'role' => Role::Coordenador,
            ])
            ->etecs()
            ->attach($etec->id, ['role' => Role::Coordenador->value]);
    }
}
