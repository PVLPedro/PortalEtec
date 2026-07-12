<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\Role;

#[Fillable(['name', 'email', 'etec_id', 'role', 'rm', 'cpf', 'telephone', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    public function etecs()
    {
        return $this->belongsToMany(Etec::class)->withPivot('rm')->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => \App\Enums\Role::class,
        ];
    }

    /**
     * Check if the user's email domain matches their role.
     */
    public function hasValidEmailDomain(): bool
    {
        $email = strtolower($this->email);

        return match ($this->role) {
            Role::Aluno => str_ends_with($email, '@aluno.cps.sp.gov.br'),
            Role::Professor, Role::Coordenador => str_ends_with($email, '@cps.gov.br'),
            default => false,
        };
    }
}