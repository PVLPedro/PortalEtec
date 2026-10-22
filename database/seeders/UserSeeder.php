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
        $etec = Etec::firstOrFail();

        // Aluno 1
        $yuji = User::factory()->create([
            'name' => 'Yuji',
            'email' => 'yuji@aluno.cps.sp.gov.br',
            'password' => 'password',
            'role' => Role::Aluno,
        ]);

        $yuji->student()->create([
            'rm' => '1234567',
        ]);

        $yuji->etecs()->attach($etec->codigo, [
            'role' => 'aluno',
        ]);

        // Aluno 2
        $samuel = User::factory()->create([
            'name' => 'samuelgato',
            'email' => 'samuelgato@aluno.cps.sp.gov.br',
            'password' => 'password',
            'role' => Role::Aluno,
        ]);

        $samuel->student()->create([
            'rm' => '1234568',
        ]);

        $samuel->etecs()->attach($etec->codigo, [
            'role' => 'aluno',
        ]);

        // Professor
        $siles = User::factory()->create([
            'name' => 'Siles',
            'email' => 'siles@cps.sp.gov.br',
            'password' => 'password4',
            'role' => Role::Professor,
        ]);

        $siles->etecs()->attach($etec->codigo, [
            'role' => 'professor',
        ]);

        // Coordenador 1
        $davi = User::factory()->create([
            'name' => 'Davi',
            'email' => 'davi@cps.sp.gov.br',
            'password' => 'password3',
            'role' => Role::Coordenador,
        ]);

        $davi->etecs()->attach($etec->codigo, [
            'role' => 'coordenador',
        ]);

        // Coordenador 2
        $afonso = User::factory()->create([
            'name' => 'Afonso',
            'email' => 'afonso@cps.sp.gov.br',
            'password' => 'password3',
            'role' => Role::Coordenador,
        ]);

        $afonso->etecs()->attach($etec->codigo, [
            'role' => 'coordenador',
        ]);

        // Coordenador 3
        $furigo = User::factory()->create([
            'name' => 'furigo',
            'email' => 'furigo@cps.sp.gov.br',
            'password' => 'password67',
            'role' => Role::Coordenador,
        ]);

        $furigo->etecs()->attach($etec->codigo, [
            'role' => 'coordenador',
        ]);
    }
}
