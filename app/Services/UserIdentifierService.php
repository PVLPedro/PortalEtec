<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class UserIdentifierService
{
    public function generateName(string $email): string
    {
        $prefix = Str::before(trim($email), '@');
        $withoutAccents = Str::ascii($prefix);

        return Str::upper($withoutAccents);
    }

    public function extractFirstName(string $name): string
    {
        $parts = preg_split('/[.\s_\-]/', trim($name));

        return $parts[0] !== '' && $parts[0] !== false ? $parts[0] : $name;
    }

    public function generateIdentifier(string $firstName, ?int $ignoreUserId = null): string
    {
        $firstName = Str::upper($this->extractFirstName($firstName));

        for ($attempt = 0; $attempt < 50; $attempt++) {
            $candidate = sprintf('%04d', random_int(0, 9999));

            if (! $this->isIdentifierTaken($firstName, $candidate, $ignoreUserId)) {
                return $candidate;
            }
        }

        return $this->findNextAvailableIdentifier($firstName, $ignoreUserId);
    }

    public function isIdentifierTaken(string $firstName, string $identifier, ?int $ignoreUserId = null): bool
    {
        return User::query()
            ->when($ignoreUserId, fn ($q) => $q->where('id', '!=', $ignoreUserId))
            ->where('identifier', $identifier)
            ->where(function ($query) use ($firstName) {
                $query->where('name', $firstName)
                    ->orWhere('name', 'like', $firstName.'.%')
                    ->orWhere('name', 'like', $firstName.' %')
                    ->orWhere('name', 'like', $firstName.'_%')
                    ->orWhere('name', 'like', $firstName.'-%');
            })
            ->exists();
    }

    private function findNextAvailableIdentifier(string $firstName, ?int $ignoreUserId = null): string
    {
        $usedIdentifiers = User::query()
            ->when($ignoreUserId, fn ($q) => $q->where('id', '!=', $ignoreUserId))
            ->whereNotNull('identifier')
            ->where(function ($query) use ($firstName) {
                $query->where('name', $firstName)
                    ->orWhere('name', 'like', $firstName.'.%')
                    ->orWhere('name', 'like', $firstName.' %')
                    ->orWhere('name', 'like', $firstName.'_%')
                    ->orWhere('name', 'like', $firstName.'-%');
            })
            ->pluck('identifier')
            ->flip()
            ->all();

        for ($i = 0; $i <= 9999; $i++) {
            $candidate = sprintf('%04d', $i);
            if (! isset($usedIdentifiers[$candidate])) {
                return $candidate;
            }
        }

        throw new \RuntimeException("Limite atingido para: {$firstName}");
    }
}
