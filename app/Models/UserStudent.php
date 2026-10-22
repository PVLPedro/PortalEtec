<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStudent extends Model
{
    use HasFactory;

    protected $table = 'user_students';

    protected $primaryKey = 'id_student';

    public $incrementing = true;

    // A tabela não possui created_at nem updated_at.
    public $timestamps = false;

    protected $fillable = ['user_id', 'id_class', 'rm'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'id_class', 'id_class');
    }
}
