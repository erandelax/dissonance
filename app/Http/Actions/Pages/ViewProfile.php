<?php

declare(strict_types=1);

namespace App\Http\Actions\Pages;

use App\Entities\DiscordID;
use App\Entities\Locale;
use App\Http\Actions\Action;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Get;

final class ViewProfile extends Action
{
    #[Get(uri: '/{locale}/u/{discordID}', name: 'profile', middleware: 'web')]
    public function __invoke(Locale $locale, DiscordID $discordID): \Illuminate\Contracts\View\View
    {
        $user = Auth::user();
        $profileOwner = User::whereDiscordId($discordID)->firstOrFail();
        return view($user->id === $profileOwner->id ? 'pages.profile_editor' : 'pages.profile', [
            'user' => $profileOwner,
        ]);
    }
}
