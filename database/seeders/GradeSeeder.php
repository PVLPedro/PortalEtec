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
        $grades = [['name' => '1º Ano'], ['name' => '2º Ano'], ['name' => '3º Ano']];

        foreach ($grades as $grade) {
            \App\Models\Grade::create($grade);
            // DB::table('grades')->insert($grade);
        }
    }
}
