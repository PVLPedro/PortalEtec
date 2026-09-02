<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStudent extends Model
{
    protected $table = 'user_students';
    protected $primaryKey = 'id_student';
    public $timestamps = false;

    protected $fillable = ['user_id', 'id_class', 'rm', 'id_side'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'id_class');
    }

    public function side(): BelongsTo
    {
        return $this->belongsTo(Side::class, 'id_side', 'id_side');
    }
}
