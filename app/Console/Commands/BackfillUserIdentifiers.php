<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\UserIdentifierService;
use Illuminate\Console\Command;

class BackfillUserIdentifiers extends Command
{
    protected $signature = 'users:backfill-identifiers';

    protected $description = 'Gera nomes e identificadores para usuarios existentes com email cadastrado';

    public function handle(UserIdentifierService $identifiers): int
    {
        $users = User::whereNotNull('email')->whereNull('identifier')->get();

        foreach ($users as $user) {
            $name = $identifiers->generateName($user->email);
            $firstName = $identifiers->extractFirstName($name);
            $identifier = $identifiers->generateIdentifier($firstName, $user->id);

            $user->forceFill([
                'name' => $name,
                'identifier' => $identifier,
            ])->save();
        }

        return self::SUCCESS;
    }
}
