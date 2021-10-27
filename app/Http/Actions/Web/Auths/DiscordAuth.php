<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Auths;

use App\Repositories\UserRepository;
use App\Utils\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use SebastianBergmann\Timer\TimeSinceStartOfRequestNotAvailableException;
use SocialiteProviders\Manager\OAuth2\User as SocialiteUser;

final class DiscordAuth
{
    public function __construct(
        private UserRepository $userRepository
    ) { }

    public function __invoke(Request $request)
    {
        $returnURL = $request->get('returnURL') ?? Session::get('request-return-url') ?? config('app.url');
        if ($request->has('returnURL')) {
            Session::put('request-return-url', $returnURL);
        }
        config()->set('services.discord.redirect', scoped_route('auths.discord'));
        try {
            /** @var SocialiteUser $socialiteUser */
            $socialiteUser = Socialite::driver('discord')->user();
        } catch (InvalidStateException) {
            return Socialite::driver('discord')->redirect();
        }
        $user = $this->userRepository->updateOrCreateByDiscordUser($socialiteUser);
        $returnHost = parse_url($returnURL, PHP_URL_HOST);
        $returnPort = parse_url($returnURL, PHP_URL_PORT);
        if ($returnHost !== $request->getHost() || $returnPort !== $request->getPort()) {
            $token = $this->userRepository->createAuthToken($user);
            $parts = explode('?', $returnURL, 2);
            $returnURL = implode('?', [$parts[0], implode('&', array_filter(["auth_token=$token", $parts[1] ?? null]))]);
        } else {
            Auth::login($user);
            (new Alert(
                content: 'You have successfully logged in to your account!',
                title: "Welcome, {$user->display_name}",
                alertType: Alert::TYPE_SUCCESS,
            ))->dispatch();
        }
        Session::forget('request-return-url');
        return redirect($returnURL);
    }
}
