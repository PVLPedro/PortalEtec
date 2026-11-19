<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'school_class_id', 'side_id', 'rm'])]
class UserStudent extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function side(): BelongsTo
    {
        return $this->belongsTo(Side::class);
    }

    public function activityStudents(): HasMany
    {
        return $this->hasMany(ActivityStudent::class, 'student_id');
    }

    protected static function booted(): void
    {
        static::creating(function (UserStudent $student) {
            $student->side_id ??= Side::where('name', 'Composto')->value('id');
        });
    }
}
