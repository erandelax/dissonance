<?php

declare(strict_types=1);

namespace App\Http\Actions\Profiles;

use App\Entities\DiscordID;
use App\Entities\Locale;
use App\Http\Actions\Action;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\RouteAttributes\Attributes\Get;

final class ViewProfile extends Action
{
    #[Get(uri: '/{locale}/u/{discordID}', name: 'profile', middleware: 'web')]
    public function __invoke(Locale $locale, DiscordID $discordID): \Illuminate\Contracts\View\View
    {
        $user = User::whereDiscordId($discordID)->firstOrFail();
        return view(Gate::check('update-user', $user) ? 'profiles.profile_editor' : 'profiles.profile', [
            'user' => $user,
        ]);
    }
}
