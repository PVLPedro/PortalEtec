<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityStudent extends Model
{
    protected $table = 'activity_students';

    protected $primaryKey = 'id_activity_student';

    public $timestamps = false;

    protected $fillable = ['id_activity', 'id_student', 'id_status', 'posted'];

    protected $casts = [
        'posted' => 'datetime',
    ];

    /**
     * Atividade a que esta entrega se refere.
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'id_activity', 'id_activity');
    }

    /**
     * Aluno que fez a entrega.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(UserStudent::class, 'id_student', 'id_student');
    }

    /**
     * Status atual da entrega.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(StatusActivity::class, 'id_status', 'id_status');
    }
}
