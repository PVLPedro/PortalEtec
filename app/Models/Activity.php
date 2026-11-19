<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['school_class_teacher_id', 'description', 'deadline'])]
class Activity extends Model
{
    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
        ];
    }

    public function schoolClassTeacher(): BelongsTo
    {
        return $this->belongsTo(SchoolClassTeacher::class);
    }

    public function activityStudents(): HasMany
    {
        return $this->hasMany(ActivityStudent::class);
    }
}
