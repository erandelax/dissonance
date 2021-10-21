<?php

declare(strict_types=1);

namespace App\Http\Actions\Auth;

use App\Http\Actions\Action;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use SocialiteProviders\Manager\OAuth2\User as SocialiteUser;
use Spatie\RouteAttributes\Attributes\Get;

final class Discord extends Action
{
    #[Get(uri: '/oauth/discord', name: 'oauth.discord', middleware: 'web')]
    public function __invoke()
    {
        try {
            /** @var SocialiteUser $socialiteUser */
            $socialiteUser = Socialite::driver('discord')->user();
        } catch (InvalidStateException $exception) {
            return Socialite::driver('discord')->redirect();
        }
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

        Auth::login($user);

        return redirect(route('home'));
    }
}
