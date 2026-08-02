<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $icons = [
            ['name' => 'Curso', 'code' => 'graduation-cap'],
            ['name' => 'Programação', 'code' => 'cpu'],
            ['name' => 'Web', 'code' => 'globe'],
            ['name' => 'Inteligência Artificial', 'code' => 'bot'],
            ['name' => 'Infraestrutura', 'code' => 'building-2'],
            ['name' => 'Games', 'code' => 'gamepad-2'],
            ['name' => 'Administração', 'code' => 'chart-no-axes-combined'],
            ['name' => 'RH', 'code' => 'contact-round'],
            ['name' => 'Marketing', 'code' => 'megaphone'],
            ['name' => 'Logística', 'code' => 'van'],
            ['name' => 'Finanças', 'code' => 'circle-dollar-sign'],
            ['name' => 'Mecânica', 'code' => 'cog'],
            ['name' => 'Eletrotécnica', 'code' => 'cable'],
            ['name' => 'Automação', 'code' => 'factory'],
            ['name' => 'Química', 'code' => 'test-tube-diagonal'],
            ['name' => 'Gastronomia', 'code' => 'soup'],
            ['name' => 'Agronegócio', 'code' => 'leaf'],
            ['name' => 'Construção', 'code' => 'hammer'],
            ['name' => 'Geoprocessamento', 'code' => 'map-pinned'],
            ['name' => 'Design', 'code' => 'palette'],
            ['name' => 'Audiovisual', 'code' => 'monitor-play'],
            ['name' => 'Eventos', 'code' => 'drama'],
            ['name' => 'Saúde', 'code' => 'heart-plus'],
            ['name' => 'Segurança', 'code' => 'shield-check'],
            ['name' => 'Turismo', 'code' => 'plane'],
        ];

        $now = now();

        $rows = array_map(function ($icon) use ($now) {
            return [
                'name' => $icon['name'],
                'code' => $icon['code'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $icons);

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('icons')->insert($chunk);
        }
    }
}
