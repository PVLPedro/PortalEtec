<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Manhã',
            'Tarde',
            'Noite',
        ];

        $now = now();

        DB::table('shifts')->insert(array_map(
            fn (string $name) => ['name' => $name, 'created_at' => $now, 'updated_at' => $now],
            $names,
        ));
    }
}
