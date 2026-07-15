<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['etec_id', 'course_id', 'grade_id', 'shift_id'])]
class SchoolClass extends Model
{
    protected function nome(): Attribute
    {
        return Attribute::get(
            fn() => "{$this->grade->name} {$this->course->course_name} - {$this->shift->name}",
        );
    }

    public function etec(): BelongsTo
    {
        return $this->belongsTo(Etec::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
