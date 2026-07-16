<?php

namespace App\Support;

use App\Enums\Role;

class EmailDomainValidator
{
    public static function isValid(string $email, Role $role): bool
    {
        return match ($role) {
            Role::Aluno => str_ends_with($email, '@etec.sp.gov.br'),
            Role::Professor, Role::Coordenador => str_ends_with($email, '@cps.sp.gov.br'),
        };
    }
}
