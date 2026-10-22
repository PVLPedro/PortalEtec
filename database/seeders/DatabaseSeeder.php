<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Dados de referência (necessários em qualquer ambiente)
        $this->call([
            EtecSeeder::class,
            SideSeeder::class,
            ShiftSeeder::class,
            GradeSeeder::class,
            CourseSeeder::class,
            DisciplineSeeder::class,
        ]);

        // Dados de demonstração (nunca em produção)
        if (! app()->isProduction()) {
            $this->call([
                SchoolClassSeeder::class,
                UserSeeder::class,
            ]);
        }
    }
}
