<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['activity_id', 'student_id', 'activity_status_id', 'posted'])]
class ActivityStudent extends Model
{
    protected function casts(): array
    {
        return [
            'posted' => 'datetime',
        ];
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(UserStudent::class, 'student_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ActivityStatus::class, 'activity_status_id');
    }
}
