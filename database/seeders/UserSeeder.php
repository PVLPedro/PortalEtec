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

        User::factory()
            ->create([
                'name' => 'Yuji',
                'email' => 'yuji@aluno.cps.sp.gov.br',
                'password' => 'password',
                'role' => Role::Aluno,
            ])
            ->etecs()
            ->attach($camargoEtec->id, ['rm' => '4444444']);

        User::factory()
            ->create([
                'name' => 'Samuel',
                'email' => 'samuel@aluno.cps.sp.gov.br',
                'password' => 'password',
                'role' => Role::Aluno,
            ])
            ->etecs()
            ->attach($camargoEtec->id, ['rm' => '5555555']);

        User::factory()
            ->create([
                'name' => 'Gustavo Rafael',
                'email' => 'gustavo.rafael@aluno.cps.sp.gov.br',
                'password' => 'password',
                'role' => Role::Aluno,
            ])
            ->etecs()
            ->attach($belemEtec->id, ['rm' => '6666666']);

        User::factory()
            ->create([
                'name' => 'Yasmim',
                'email' => 'yasmim@aluno.cps.sp.gov.br',
                'password' => 'password',
                'role' => Role::Aluno,
            ])
            ->etecs()
            ->attach($belemEtec->id, ['rm' => '7777777']);

        User::factory()
            ->create([
                'name' => 'Carolina',
                'email' => 'carolina@aluno.cps.sp.gov.br',
                'password' => 'password',
                'role' => Role::Aluno,
            ])
            ->etecs()
            ->attach($belemEtec->id, ['rm' => '8888888']);

        User::factory()
            ->create([
                'name' => 'Eduardo',
                'email' => 'eduardo@aluno.cps.sp.gov.br',
                'password' => 'password',
                'role' => Role::Aluno,
            ])
            ->etecs()
            ->attach($belemEtec->id, ['rm' => '9999999']);

        User::factory()
            ->create([
                'name' => 'Siles',
                'email' => 'siles@cps.sp.gov.br',
                'password' => 'password4',
                'role' => Role::Professor,
            ])
            ->etecs()
            ->attach($camargoEtec->id);

        User::factory()
            ->create([
                'name' => 'Davi',
                'email' => 'davi@cps.sp.gov.br',
                'password' => 'password3',
                'role' => Role::Coordenador,
            ])
            ->etecs()
            ->attach($camargoEtec->id);

        User::factory()
            ->create([
                'name' => 'Afonso',
                'email' => 'afonso@cps.sp.gov.br',
                'password' => 'password3',
                'role' => Role::Coordenador,
            ])
            ->etecs()
            ->attach($camargoEtec->id);

        User::factory()
            ->create([
                'name' => 'furigo',
                'email' => 'furigo@cps.sp.gov.br',
                'password' => 'password67',
                'role' => Role::Coordenador,
            ])
            ->etecs()
            ->attach($belemEtec->id);
    }
}
