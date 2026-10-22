<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Dados de demonstração (só ambiente de desenvolvimento).
 * Cria uma turma para que os alunos do UserSeeder tenham a qual pertencer.
 */
class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('school_classes')->insert([
            'etec_id' => DB::table('etecs')->orderBy('id')->value('id'),
            'course_id' => DB::table('courses')->where('initialism', 'DS')->value('id'),
            'grade_id' => DB::table('grades')->where('name', '1º Ano')->value('id'),
            'shift_id' => DB::table('shifts')->where('name', 'Manhã')->value('id'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
