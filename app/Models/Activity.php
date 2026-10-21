<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    protected $table = 'activity';

    protected $primaryKey = 'id_activity';

    public $timestamps = false;

    protected $fillable = [
        'school_class_user_id',
        'descriptive',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    /**
     * Turma/disciplina/professor à qual esta atividade pertence.
     */
    public function schoolClassUser(): BelongsTo
    {
        return $this->belongsTo(SchoolClassUser::class, 'school_class_user_id');
    }

    /**
     * Entregas dos alunos para esta atividade.
     */
    public function activityStudents(): HasMany
    {
        return $this->hasMany(ActivityStudent::class, 'id_activity', 'id_activity');
    }
}<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    protected $table = 'activity';

    protected $primaryKey = 'id_activity';

    public $timestamps = false;

    protected $fillable = [
        'school_class_user_id',
        'descriptive',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    /**
     * Turma/disciplina/professor à qual esta atividade pertence.
     */
    public function schoolClassUser(): BelongsTo
    {
        return $this->belongsTo(SchoolClassUser::class, 'school_class_user_id');
    }

    /**
     * Entregas dos alunos para esta atividade.
     */
    public function activityStudents(): HasMany
    {
        return $this->hasMany(ActivityStudent::class, 'id_activity', 'id_activity');
    }
}
