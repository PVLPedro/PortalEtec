<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Etec;
use App\Models\User;
use App\Services\UserIdentifierService;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $etec = Etec::where('code', 6)->firstOrFail();
        $identifiers = app(UserIdentifierService::class);

        $students = [
            [
                'name' => 'Yuji',
                'rm' => 1234567,
                'email' => null,
                'email_verified_at' => null,
                'first_access_status' => 'email_pendente',
            ],
            [
                'name' => 'SAMUELGATO',
                'rm' => 1234568,
                'email' => 'samuelgato@aluno.cps.sp.gov.br',
                'email_verified_at' => now(),
                'first_access_status' => 'concluido',
            ],
        ];

        foreach ($students as $data) {
            $identifier = $data['email']
                ? $identifiers->generateIdentifier($identifiers->extractFirstName($data['name']))
                : null;

            $user = User::unguarded(
                fn () => User::create([
                    'name' => $data['name'],
                    'identifier' => $identifier,
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

        $siles = $this->verifiedUser($identifiers, 'siles@cps.sp.gov.br', 'password4', Role::Professor);
        $siles->etecs()->attach($etec->id, ['role' => Role::Professor->value]);
        $siles->teacher()->create();

        $coordinators = [
            ['email' => 'davi@cps.sp.gov.br', 'password' => 'password3'],
            ['email' => 'afonso@cps.sp.gov.br', 'password' => 'password3'],
            ['email' => 'furigo@cps.sp.gov.br', 'password' => 'password67'],
        ];

        foreach ($coordinators as $data) {
            $user = $this->verifiedUser(
                $identifiers,
                $data['email'],
                $data['password'],
                Role::Coordenador,
            );
            $user->etecs()->attach($etec->id, ['role' => Role::Coordenador->value]);
            $user->coordinator()->create();
        }
    }

    private function verifiedUser(
        UserIdentifierService $identifiers,
        string $email,
        string $password,
        Role $role,
    ): User {
        $name = $identifiers->generateName($email);
        $firstName = $identifiers->extractFirstName($name);
        $identifier = $identifiers->generateIdentifier($firstName);

        return User::unguarded(
            fn () => User::create([
                'name' => $name,
                'identifier' => $identifier,
                'email' => $email,
                'email_verified_at' => now(),
                'password' => $password,
                'role' => $role,
            ]),
        );
    }
}
