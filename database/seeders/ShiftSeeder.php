<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = [['name' => 'Manhã'], ['name' => 'Tarde'], ['name' => 'Noite']];

        foreach ($shifts as $shift) {
            \App\Models\Shift::create($shift);
            // DB::table('shifts')->insert($shift);
        }
    }
}
