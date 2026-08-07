<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    protected $fillable = ['initialism', 'name', 'icon_id'];

    public function icon(): BelongsTo
    {
        return $this->belongsTo(Icon::class);
    }
}
