<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

#[Fillable(['school_class_id', 'discipline_id', 'color_id'])]
class SchoolClassDiscipline extends Model
{
    protected $table = 'school_class_discipline';

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'school_class_discipline_id');
    }

    public function teachers(): Builder
    {
        return DB::table('school_class_teacher')
            ->join('users', 'users.id', '=', 'school_class_teacher.teacher_id')
            ->where('school_class_teacher.school_class_id', $this->school_class_id)
            ->where('school_class_teacher.discipline_id', $this->discipline_id)
            ->select('users.id', 'users.name', 'school_class_teacher.side_id')
            ->orderBy('users.name');
    }

    public function isCourseDiscipline(): bool
    {
        return $this->discipline->course_id !== null;
    }
}
