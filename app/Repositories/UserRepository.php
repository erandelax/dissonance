<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\DiscordID;
use App\Entities\UserReference;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use \SocialiteProviders\Manager\OAuth2\User as SocialiteUser;

final class UserRepository
{
    /**
     * @param \App\Entities\UserReference $userReference
     *
     * @return \App\Models\User|null
     */
    public function findByReference(UserReference $userReference): ?User
    {
        return User::find($userReference);
    }

    public function findByDiscordID(DiscordID $discordID): ?User
    {
        return User::whereDiscordId($discordID)->first();
    }

    /**
     * @param \SocialiteProviders\Manager\OAuth2\User $socialiteUser
     *
     * @return \App\Models\User
     */
    public function updateOrCreateByDiscordUser(SocialiteUser $socialiteUser): User
    {
        $user = User::whereDiscordId($socialiteUser->getId())->first();
        $raw = $socialiteUser->getRaw();
        if (null === $user) {
            $user = User::create([
                'name' => $socialiteUser->getName(),
                'email' => $socialiteUser->getEmail(),
                'nickname' => $socialiteUser->getNickname() ?? $socialiteUser->getName(),
                'avatar' => $socialiteUser->getAvatar(),
                'discord_id' => $socialiteUser->getId(),
                'banner' => $raw['banner'] ?? null,
                'banner_color' => $raw['banner_color'] ?? null,
                'accent_color' => $raw['accent_color'] ?? null,
                'password' => Hash::make($socialiteUser->token),
                'email_verified_at' => Carbon::now(),
                'locale' => app()->getLocale(),
            ]);
        } else {
            $user->update([
                'email' => $socialiteUser->getEmail(),
                'nickname' => $socialiteUser->getNickname() ?? $socialiteUser->getName(),
                'discord_id' => $socialiteUser->getId(),
            ]);
        }
        return $user;
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \App\Models\User
     */
    public function createAuthToken(User $user): string
    {
        $user->auth_token = Str::random(40) . $user->getKey() . Str::random(40);
        $user->save();
        return $user->auth_token;
    }

    /**
     * @param string $token
     *
     * @return \App\Models\User|null
     */
    public function findAndForgetByAuthToken(string $token): User|null
    {
        $user = User::whereAuthToken($token)->first();
        if (null !== $user) {
            $user->auth_token = null;
            $user->save();
        }
        return $user;
    }
}
