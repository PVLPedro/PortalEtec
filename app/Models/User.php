<?php

namespace App\Models;

use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'role', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    public function student(): HasOne
    {
        return $this->hasOne(UserStudent::class);
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(UserTeacher::class);
    }

    public function coordinator(): HasOne
    {
        return $this->hasOne(UserCoordinator::class);
    }

    public function teachingClasses(): BelongsToMany
    {
        return $this->belongsToMany(
            SchoolClass::class,
            'school_class_user',
            'id_teacher',
            'school_class_id',
        );
    }

    public function etecs(): BelongsToMany
    {
        return $this->belongsToMany(Etec::class, 'etec_worker', 'user_id', 'id_etec');
    }

    public function activeEtec()
    {
        return $this->etecs()->first();
    }

    public function hasValidEmailDomain(): bool
    {
        $email = strtolower($this->email);

        return match ($this->role) {
            Role::Aluno => str_ends_with($email, '@aluno.cps.sp.gov.br'),
            Role::Professor, Role::Coordenador => str_ends_with($email, '@cps.sp.gov.br'),
            default => false,
        };
    }

    public function isStudent(): bool
    {
        return $this->role === Role::Aluno;
    }

    public function isTeacher(): bool
    {
        return $this->role === Role::Professor;
    }

    public function isCoordinator(): bool
    {
        return $this->role === Role::Coordenador;
    }
}
