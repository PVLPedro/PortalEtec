<?php

namespace App\Models;

use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
