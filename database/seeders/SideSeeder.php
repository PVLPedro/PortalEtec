<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SideSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Turma A',
            'Turma B',
            'Composto',
        ];

        $now = now();

        DB::table('sides')->insert(array_map(
            fn (string $name) => ['name' => $name, 'created_at' => $now, 'updated_at' => $now],
            $names,
        ));
    }
}
