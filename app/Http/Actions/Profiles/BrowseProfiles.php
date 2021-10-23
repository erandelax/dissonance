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

final class BrowseProfiles extends Action
{
    #[Get(uri: '/{locale}/u', name: 'profile.list', middleware: 'web')]
    public function __invoke(Locale $locale, DiscordID $discordID): \Illuminate\Contracts\View\View
    {
        $users = User::paginate(10);
        return view('profiles.profile_list', [
            'users' => $users,
        ]);
    }
}
