<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Course;
use App\Models\Discipline;
use App\Models\Etec;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $schoolClass = SchoolClass::create([
            'etec_id' => Etec::where('code', 6)->value('id'),
            'course_id' => Course::where('name', 'Desenvolvimento de Sistemas')->value('id'),
            'grade_id' => Grade::where('name', '3º Ano')->value('id'),
            'shift_id' => Shift::where('name', 'Manhã')->value('id'),
            'color_id' => Color::where('code', 'dark-blue')->value('id'),
        ]);

        User::whereIn('name', ['Yuji', 'samuelgato'])->get()->each(function (User $user) use (
            $schoolClass,
        ) {
            $user->student()->update(['school_class_id' => $schoolClass->id]);
        });

        $teacher = User::where('email', 'siles@cps.sp.gov.br')->firstOrFail();
        $discipline = Discipline::where('name', 'Matemática')->firstOrFail();

        $schoolClass->teacherAssignments()->create([
            'teacher_id' => $teacher->id,
            'discipline_id' => $discipline->id,
        ]);
    }
}
