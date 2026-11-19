<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name'])]
class Etec extends Model
{
    public function schoolClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'etec_user')
            ->withPivot('rm', 'role')
            ->withTimestamps();
    }
}
