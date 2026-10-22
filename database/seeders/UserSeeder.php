<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Usuários de demonstração (só ambiente de desenvolvimento).
 * Todos usam a senha "password".
 */
class UserSeeder extends Seeder
{
    private const PASSWORD = 'password';

    public function run(): void
    {
        $now = now();
        $timestamps = ['created_at' => $now, 'updated_at' => $now];

        $etecId = DB::table('etecs')->orderBy('id')->value('id');
        $schoolClassId = DB::table('school_classes')->orderBy('id')->value('id');

        $students = [
            ['Yuji', 'yuji@aluno.cps.sp.gov.br', 1234567],
            ['samuelgato', 'samuelgato@aluno.cps.sp.gov.br', 1234568],
        ];

        foreach ($students as [$name, $email, $rm]) {
            $user = $this->createUser($name, $email, Role::Aluno);

            DB::table('user_students')->insert([
                'user_id' => $user->id,
                'school_class_id' => $schoolClassId,
                'rm' => $rm,
            ] + $timestamps);

            DB::table('school_class_student')->insert([
                'school_class_id' => $schoolClassId,
                'user_id' => $user->id,
            ] + $timestamps);
        }

        $user = $this->createUser('Siles', 'siles@cps.sp.gov.br', Role::Professor);

        DB::table('user_teachers')->insert(['user_id' => $user->id] + $timestamps);
        $this->attachToEtec($user, $etecId, Role::Professor, $timestamps);

        $coordinators = [
            ['Davi', 'davi@cps.sp.gov.br'],
            ['Afonso', 'afonso@cps.sp.gov.br'],
            ['furigo', 'furigo@cps.sp.gov.br'],
        ];

        foreach ($coordinators as [$name, $email]) {
            $user = $this->createUser($name, $email, Role::Coordenador);

            DB::table('user_coordinators')->insert(['user_id' => $user->id] + $timestamps);
            $this->attachToEtec($user, $etecId, Role::Coordenador, $timestamps);
        }
    }

    private function createUser(string $name, string $email, Role $role): User
    {
        return User::factory()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make(self::PASSWORD),
            'role' => $role,
        ]);
    }

    private function attachToEtec(User $user, int $etecId, Role $role, array $timestamps): void
    {
        DB::table('etec_user')->insert([
            'user_id' => $user->id,
            'etec_id' => $etecId,
            'role' => $role->value,
        ] + $timestamps);
    }
}
