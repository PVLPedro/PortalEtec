<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('side')->insert([
            ['id_side' => 1, 'side_name' => 'Turma A'],
            ['id_side' => 2, 'side_name' => 'Turma B'],
            ['id_side' => 3, 'side_name' => 'Composto'],
        ]);
    }
}
