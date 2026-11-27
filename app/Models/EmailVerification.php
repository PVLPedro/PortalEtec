<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'email',
    'purpose',
    'code',
    'attempts',
    'sent_at',
    'expires_at',
    'cancelled_at',
])]
class EmailVerification extends Model
{
    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'expires_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
