<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            '1º Ano',
            '2º Ano',
            '3º Ano',
        ];

        $now = now();

        DB::table('grades')->insert(array_map(
            fn (string $name) => ['name' => $name, 'created_at' => $now, 'updated_at' => $now],
            $names,
        ));
    }
}
