<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'school_class_discipline_id',
    'author_id',
    'side_id',
    'title',
    'description',
    'deadline',
    'comments_enabled',
])]
class Activity extends Model
{
    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'comments_enabled' => 'boolean',
        ];
    }

    public function isMaterial(): bool
    {
        return $this->deadline === null;
    }

    public function schoolClassDiscipline(): BelongsTo
    {
        return $this->belongsTo(SchoolClassDiscipline::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function side(): BelongsTo
    {
        return $this->belongsTo(Side::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ActivityFile::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function activityStudents(): HasMany
    {
        return $this->hasMany(ActivityStudent::class);
    }

    public function destroyWithFiles(): void
    {
        $paths = $this->files()->pluck('path')->all();

        DB::transaction(fn () => $this->delete());

        Storage::disk(ActivityFile::DISK)->delete($paths);
    }
}
