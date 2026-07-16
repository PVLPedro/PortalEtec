<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\Role;
use App\Models\Etec;
use App\Models\SchoolClass;

#[Fillable(['name', 'email', 'etec_id', 'role', 'rm', 'password'])]
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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => \App\Enums\Role::class,
        ];
    }

    public function etecs(): BelongsToMany
    {
        return $this->belongsToMany(Etec::class, 'etec_user')->withPivot('rm');
    }

    // Etec "ativa" na sessão, com fallback pra primeira vinculada
    public function activeEtec(): ?Etec
    {
        $activeId = session('etec_ativa');

        if ($activeId && $this->etecs->contains($activeId)) {
            return $this->etecs->find($activeId);
        }

        return $this->etecs->first();
    }

    public function belongsToMultipleEtecs(): bool
    {
        return $this->role !== Role::Aluno;
    }

    public function schoolClasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class)->withTimestamps();
    }

    /**
     * Checa se o domínio do email do usuário é válido para o cargo dele.
     */
    public function hasValidEmailDomain(): bool
    {
        $email = strtolower($this->email);

        return match ($this->role) {
            Role::Aluno => str_ends_with($email, '@aluno.cps.sp.gov.br'),
            Role::Professor, Role::Coordenador => str_ends_with($email, '@cps.sp.gov.br'),
            default => false,
        };
    }
}
