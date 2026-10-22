<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisciplineSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
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

        $now = now();

        DB::table('disciplines')->insert(array_map(
            fn (string $name) => ['name' => $name, 'created_at' => $now, 'updated_at' => $now],
            $names,
        ));
    }
}
