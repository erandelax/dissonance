<?php

declare(strict_types=1);

namespace App\Http\Actions\Auth;

use App\Http\Actions\Action;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Get;

final class Logout extends Action
{
    #[Get(uri: '/oauth/logout', name: 'oauth.logout', middleware: 'web')]
    public function __invoke(): RedirectResponse
    {
        Auth::logout();

        return redirect(route('home'));
    }
}
