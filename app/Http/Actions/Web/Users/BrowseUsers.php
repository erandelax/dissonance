<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Users;

use App\Entities\LocaleReference;
use App\Entities\ProjectReference;
use App\Models\User;

final class BrowseUsers
{
    public function __invoke(ProjectReference $project, LocaleReference $locale)
    {
        $users = User::paginate(10);
        return view('web.users.browse', [
            'users' => $users,
        ]);
    }
}
