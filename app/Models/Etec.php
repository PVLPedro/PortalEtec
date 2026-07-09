<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etec extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('rm')->withTimestamps();
    }
}
