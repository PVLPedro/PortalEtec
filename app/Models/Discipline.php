<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name'])]
class Discipline extends Model
{
    public function schoolClasses(): BelongsToMany
    {
        return $this->belongsToMany(
            SchoolClass::class,
            'school_class_discipline',
        )->withTimestamps();
    }
}
