<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Icon extends Model
{
    protected $fillable = ['id', 'name', 'code'];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
