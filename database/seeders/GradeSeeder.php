<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            ['name' => '1º Ano'],
            ['name' => '2º Ano'],
            ['name' => '3º Ano'],
            ['name' => '1º Semestre'],
            ['name' => '2º Semestre'],
            ['name' => '3º Semestre'],
            ['name' => '4º Semestre'],
        ];

        foreach ($grades as $grade) {
            \App\Models\Grade::create($grade);
            // DB::table('grades')->insert($grade);
        }
    }
}
