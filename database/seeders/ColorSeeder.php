<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Vermelho', 'code' => 'red'],
            ['name' => 'Laranja', 'code' => 'orange'],
            ['name' => 'Amarelo', 'code' => 'yellow'],
            ['name' => 'Lima', 'code' => 'lime'],
            ['name' => 'Verde', 'code' => 'green'],
            ['name' => 'Turquesa', 'code' => 'turquoise'],
            ['name' => 'Ciano', 'code' => 'cyan'],
            ['name' => 'Azul Claro', 'code' => 'light-blue'],
            ['name' => 'Azul Escuro', 'code' => 'dark-blue'],
            ['name' => 'Roxo', 'code' => 'purple'],
            ['name' => 'Magenta', 'code' => 'magenta'],
            ['name' => 'Rosa', 'code' => 'pink'],
            ['name' => 'Marrom', 'code' => 'brown'],
            ['name' => 'Cinza', 'code' => 'gray'],
        ];

        $now = now();

        $rows = array_map(function ($color) use ($now) {
            return [
                'name' => $color['name'],
                'code' => $color['code'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $colors);

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('colors')->insert($chunk);
        }
    }
}
