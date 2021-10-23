<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\DiscordID;
use App\Models\User;

final class UserRepository
{
    public function findByDiscordID(DiscordID $discordID): ?User
    {
        return User::whereDiscordId($discordID)->first();
    }
}
