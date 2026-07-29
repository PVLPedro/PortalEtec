<?php

namespace Database\Seeders;

use App\Models\Discipline;
use Illuminate\Database\Seeder;

class DisciplineSeeder extends Seeder
{
    public function run(): void
    {
        $disciplines = [
            'Língua Portuguesa',
            'Matemática',
            'História',
            'Geografia',
            'Ciências',
            'Biologia',
            'Física',
            'Química',
            'Língua Inglesa',
            'Educação Física',
            'Arte',
            'Filosofia',
            'Sociologia',
        ];

        foreach ($disciplines as $discipline) {
            Discipline::updateOrCreate(['name' => $discipline]);
        }
    }
}
