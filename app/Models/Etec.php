<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etec extends Model
{
    public function schoolClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('rm')->withTimestamps();
    }
}
