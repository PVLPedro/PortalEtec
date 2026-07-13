<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['etec_id', 'curso', 'serie', 'turno'])]
class SchoolClass extends Model
{
    protected function nome(): Attribute
    {
        return Attribute::get(fn() => "{$this->serie} {$this->curso} - {$this->turno}");
    }

    public function etec(): BelongsTo
    {
        return $this->belongsTo(Etec::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
