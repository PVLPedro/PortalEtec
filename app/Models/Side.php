<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Side extends Model
{
    protected $table = 'side';
    protected $primaryKey = 'id_side';
    public $timestamps = false;

    protected $fillable = ['side_name'];

    public function students(): HasMany
    {
        return $this->hasMany(UserStudent::class, 'id_side', 'id_side');
    }
}
