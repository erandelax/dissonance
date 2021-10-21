<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Entities\Locale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

final class SubstituteLocale
{
    public function handle(Request $request, \Closure $next)
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var Locale $locale */
        $locale = $request->route('locale') ?? null;
        if (null === $locale && $request->route()?->getName() === 'home') {
            return redirect(route('home', ['locale' => config('app.locale')]));
        }
        if (null === $locale) {
            $locale = $user?->locale;
        }
        if (null === $locale) {
            $locale = app()->make(Locale::class);
            $locale->setLocale(app()->getLocale());
            $request->merge(['locale' => $locale]);
        }
        if (Lang::hasForLocale('app.locale', (string)$locale)) {
            app()->setLocale((string)$locale);
        } else {
            $locale->setLocale(config('app.locale'));
        }
        $request->merge(['locale' => $locale]);
        app()->setLocale((string)$locale);

        if ($user !== null && (string)$locale !== $user->locale) {
            $user->locale = (string)$locale;
            $user->save();
        }

        return $next($request);
    }
}
