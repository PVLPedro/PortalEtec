<?php

namespace Database\Seeders;

use App\Models\Side;
use Illuminate\Database\Seeder;

class SideSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Turma A', 'Turma B', 'Composto'] as $name) {
            Side::firstOrCreate(['name' => $name]);
        }
    }
}
