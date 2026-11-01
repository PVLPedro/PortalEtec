<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['description'])]
class ActivityStatus extends Model
{
    public function activityStudents(): HasMany
    {
        return $this->hasMany(ActivityStudent::class);
    }
}
