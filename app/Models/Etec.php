<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etec extends Model
{
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'etec_worker',
            'id_etec',
            'id_user',
            'codigo',
            'id',
        )->withPivot('role');
    }
}
