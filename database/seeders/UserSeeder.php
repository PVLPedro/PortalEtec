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
            [
                'name' => 'Yuji',
                'rm' => 1234567,
                'email' => null,
                'email_verified_at' => null,
                'first_access_status' => 'email_pendente',
            ],
            [
                'name' => 'samuelgato',
                'rm' => 1234568,
                'email' => 'samuelgato@aluno.cps.sp.gov.br',
                'email_verified_at' => now(),
                'first_access_status' => 'concluido',
            ],
        ];

        foreach ($students as $data) {
            $user = User::unguarded(
                fn() => User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'email_verified_at' => $data['email_verified_at'],
                    'password' => 'password',
                    'initial_password' => 'password',
                    'role' => Role::Aluno,
                    'first_access_status' => $data['first_access_status'],
                ]),
            );

            $user->etecs()->attach($etec->id, [
                'rm' => $data['rm'],
                'role' => Role::Aluno->value,
            ]);

            $user->student()->create(['rm' => $data['rm']]);
        }

        $siles = $this->verifiedUser('Siles', 'siles@cps.sp.gov.br', 'password4', Role::Professor);
        $siles->etecs()->attach($etec->id, ['role' => Role::Professor->value]);
        $siles->teacher()->create();

        $coordinators = [
            ['name' => 'Davi', 'email' => 'davi@cps.sp.gov.br', 'password' => 'password3'],
            ['name' => 'Afonso', 'email' => 'afonso@cps.sp.gov.br', 'password' => 'password3'],
            ['name' => 'furigo', 'email' => 'furigo@cps.sp.gov.br', 'password' => 'password67'],
        ];

        foreach ($coordinators as $data) {
            $user = $this->verifiedUser(
                $data['name'],
                $data['email'],
                $data['password'],
                Role::Coordenador,
            );
            $user->etecs()->attach($etec->id, ['role' => Role::Coordenador->value]);
            $user->coordinator()->create();
        }
    }

    private function verifiedUser(string $name, string $email, string $password, Role $role): User
    {
        return User::unguarded(
            fn() => User::create([
                'name' => $name,
                'email' => $email,
                'email_verified_at' => now(),
                'password' => $password,
                'role' => $role,
            ]),
        );
    }
}
