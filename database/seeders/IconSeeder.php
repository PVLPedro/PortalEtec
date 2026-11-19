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
            ['id' => 0, 'name' => 'Curso', 'code' => 'graduation-cap'],
            ['id' => 1, 'name' => 'Programação', 'code' => 'cpu'],
            ['id' => 2, 'name' => 'Web', 'code' => 'globe'],
            ['id' => 3, 'name' => 'Inteligência Artificial', 'code' => 'bot'],
            ['id' => 4, 'name' => 'Infraestrutura', 'code' => 'building-2'],
            ['id' => 5, 'name' => 'Games', 'code' => 'gamepad-2'],
            ['id' => 6, 'name' => 'Administração', 'code' => 'chart-no-axes-combined'],
            ['id' => 7, 'name' => 'RH', 'code' => 'contact-round'],
            ['id' => 8, 'name' => 'Marketing', 'code' => 'megaphone'],
            ['id' => 9, 'name' => 'Logística', 'code' => 'van'],
            ['id' => 10, 'name' => 'Finanças', 'code' => 'circle-dollar-sign'],
            ['id' => 11, 'name' => 'Mecânica', 'code' => 'cog'],
            ['id' => 12, 'name' => 'Eletrotécnica', 'code' => 'cable'],
            ['id' => 13, 'name' => 'Automação', 'code' => 'factory'],
            ['id' => 14, 'name' => 'Química', 'code' => 'test-tube-diagonal'],
            ['id' => 15, 'name' => 'Gastronomia', 'code' => 'soup'],
            ['id' => 16, 'name' => 'Agronegócio', 'code' => 'leaf'],
            ['id' => 17, 'name' => 'Construção', 'code' => 'hammer'],
            ['id' => 18, 'name' => 'Geoprocessamento', 'code' => 'map-pinned'],
            ['id' => 19, 'name' => 'Design', 'code' => 'palette'],
            ['id' => 20, 'name' => 'Audiovisual', 'code' => 'monitor-play'],
            ['id' => 21, 'name' => 'Eventos', 'code' => 'drama'],
            ['id' => 22, 'name' => 'Saúde', 'code' => 'heart-plus'],
            ['id' => 23, 'name' => 'Segurança', 'code' => 'shield-check'],
            ['id' => 24, 'name' => 'Turismo', 'code' => 'plane'],
            ['id' => 25, 'name' => 'Matemática', 'code' => 'drafting-compass'],
            ['id' => 26, 'name' => 'Português', 'code' => 'book-open-text'],
            ['id' => 27, 'name' => 'Física', 'code' => 'atom'],
            ['id' => 28, 'name' => 'Químico', 'code' => 'flask-conical'],
            ['id' => 29, 'name' => 'Biologia', 'code' => 'sprout'],
            ['id' => 30, 'name' => 'Geografia', 'code' => 'earth'],
            ['id' => 31, 'name' => 'História', 'code' => 'hourglass'],
            ['id' => 32, 'name' => 'Filosofia', 'code' => 'lightbulb'],
            ['id' => 33, 'name' => 'Sociologia', 'code' => 'message-square-quote'],
            ['id' => 34, 'name' => 'Arte', 'code' => 'palette'],
            ['id' => 35, 'name' => 'Inglês', 'code' => 'globe'],
            ['id' => 36, 'name' => 'Educação Física', 'code' => 'sport-shoe'],
        ];

        $now = now();

        $rows = array_map(function ($icon) use ($now) {
            return [
                'id' => $icon['id'],
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
