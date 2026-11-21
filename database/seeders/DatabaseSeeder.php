<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EtecSeeder::class,
            ShiftSeeder::class,
            GradeSeeder::class,
            ColorSeeder::class,
            IconSeeder::class,
            CourseSeeder::class,
            SideSeeder::class,
            DisciplineSeeder::class,
            ActivityStatusSeeder::class,
            UserSeeder::class,
            SchoolClassSeeder::class,
            ClassContentSeeder::class,
        ]);
    }
}
