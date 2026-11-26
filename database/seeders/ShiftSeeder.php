<?php

namespace Database\Seeders;

use App\Models\Shift;
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
            Shift::create($shift);
            // DB::table('shifts')->insert($shift);
        }
    }
}
