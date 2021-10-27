<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use App\Utils\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class CrossProjectAuthentication
{
    public function __construct(
        private UserRepository $userRepository,
    )
    {
    }

    public function handle(Request $request, \Closure $next)
    {
        $token = $request->get('auth_token');
        if (null !== $token) {
            $user = $this->userRepository->findAndForgetByAuthToken(substr($token, 0, 255));
            if (null !== $user) {
                Auth::login($user);
                (new Alert(
                    content: 'You have successfully logged in to your account!',
                    title: "Welcome, {$user->display_name}",
                    alertType: Alert::TYPE_SUCCESS,
                ))->dispatch();
            }
        }
        return $next($request);
    }
}
