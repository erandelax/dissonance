<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Auths;

use App\Entities\ProjectReference;
use App\Utils\Alert;
use Illuminate\Support\Facades\Auth;

final class Logout
{
    /**
     * @param \App\Entities\ProjectReference $project
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(ProjectReference $project)
    {
        Auth::logout();
        (new Alert(
            content: 'You have successfully logged out your account.',
            title: 'See you later!',
            alertType: Alert::TYPE_SUCCESS,
        ))->dispatch();
        return redirect()->back();
    }
}
