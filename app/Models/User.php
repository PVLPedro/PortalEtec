<?php namespace App\Models;

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
        return $this->hasOne(UserStudent::class, 'user_id', 'id');
    }

    public function teacher(): HasOne
    {
        return $this->hasOne(UserTeacher::class, 'user_id', 'id');
    }

    public function coordinator(): HasOne
    {
        return $this->hasOne(UserCoordinator::class, 'user_id', 'id');
    }

    public function etecs(): BelongsToMany
    {
        return $this->belongsToMany(
            Etec::class,
            'etec_worker',
            'id_user',
            'id_etec',
            'id',
            'codigo',
        )->withPivot('role');
    }

    public function activeEtec(): ?Etec
    {
        return $this->etecs()->first();
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

    public function hasValidEmailDomain(): bool
    {
        $domain = substr(strrchr($this->email, '@') ?: '', 1);

        return in_array($domain, ['aluno.cps.sp.gov.br', 'cps.sp.gov.br'], true);
    }
}
