<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

#[Fillable(['etec_id', 'course_id', 'grade_id', 'shift_id', 'color_id'])]
class SchoolClass extends Model
{
    protected function name(): Attribute
    {
        return Attribute::get(
            fn() => "{$this->grade->name} {$this->course->name} - {$this->shift->name}",
        );
    }

    public function etec(): BelongsTo
    {
        return $this->belongsTo(Etec::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function icon(): HasOneThrough
    {
        return $this->hasOneThrough(Icon::class, Course::class, 'id', 'id', 'course_id', 'icon_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
