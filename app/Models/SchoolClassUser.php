<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClassUser extends Model
{
    // Necessário revisar este model

    protected $table = 'school_class_user';

    protected $fillable = ['school_class_id', 'id_teacher'];

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_teacher');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'school_class_user_id');
    }
}
