<?php

namespace App\Models;

use Closure;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['activity_id', 'original_name', 'path', 'mime', 'size'])]
class ActivityFile extends Model
{
    public const DISK = 'local';

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public static function pathsWhereActivity(Closure $constraint): array
    {
        return static::whereHas('activity', $constraint)->pluck('path')->all();
    }
}
