<?php

namespace Database\Seeders;

use App\Models\ActivityStatus;
use Illuminate\Database\Seeder;

class ActivityStatusSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Pendente', 'Entregue', 'Atrasada', 'Corrigida'] as $description) {
            ActivityStatus::firstOrCreate(['description' => $description]);
        }
    }
}
