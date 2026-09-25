<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusActivity extends Model
{
    protected $table = 'status_activity';

    protected $primaryKey = 'id_status';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = ['id_status', 'descriptive'];

    /**
     * Entregas de atividades que estão com este status.
     */
    public function activityStudents(): HasMany
    {
        return $this->hasMany(ActivityStudent::class, 'id_status', 'id_status');
    }
}
