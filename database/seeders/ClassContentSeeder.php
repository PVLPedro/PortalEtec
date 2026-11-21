<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Announcement;
use App\Models\Color;
use App\Models\Discipline;
use App\Models\SchoolClass;
use App\Models\SchoolClassDiscipline;
use App\Models\Side;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClassContentSeeder extends Seeder
{
    public function run(): void
    {
        $schoolClass = SchoolClass::first();

        if (!$schoolClass) {
            return;
        }

        $math = SchoolClassDiscipline::firstOrCreate(
            [
                'school_class_id' => $schoolClass->id,
                'discipline_id' => Discipline::where('name', 'Matemática')->value('id'),
            ],
            ['color_id' => Color::where('code', 'dark-blue')->value('id')],
        );

        SchoolClassDiscipline::firstOrCreate(
            [
                'school_class_id' => $schoolClass->id,
                'discipline_id' => Discipline::where('name', 'Língua Portuguesa')->value('id'),
            ],
            ['color_id' => Color::where('code', 'green')->value('id')],
        );

        $siles = User::where('email', 'siles@cps.sp.gov.br')->first();

        Announcement::create([
            'school_class_id' => $schoolClass->id,
            'author_id' => $siles?->id,
            'title' => 'Bem-vindos!',
            'body' => 'Este é um comunicado de exemplo para a turma.',
        ]);

        Activity::create([
            'school_class_discipline_id' => $math->id,
            'author_id' => $siles?->id,
            'title' => 'Lista de exercícios 1',
            'description' => 'Resolver os exercícios da página 10.',
            'deadline' => now()->addWeek(),
            'comments_enabled' => true,
        ]);

        Activity::create([
            'school_class_discipline_id' => $math->id,
            'author_id' => $siles?->id,
            'side_id' => Side::where('name', 'Turma A')->value('id'),
            'title' => 'Atividade só da Turma A',
            'description' => 'Visível apenas para alunos do lado A.',
            'deadline' => now()->addDays(3),
            'comments_enabled' => false,
        ]);
    }
}
