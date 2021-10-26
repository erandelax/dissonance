<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Entities\Locale;
use App\Entities\ProjectReference;
use App\Factories\ScopedRouteFactory;
use App\Models\Config;
use App\Models\User;
use App\Repositories\ProjectRepository;
use App\Repositories\ConfigRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;

final class SubstituteRequestEntities
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ProjectReference  $projectReference,
        private ConfigRepository  $configRepository,
    ) {}

    public function handle(Request $request, \Closure $next)
    {
        /** @var User $user */
        $user = Auth::user();

        //
        // Setup routes
        //
        ScopedRouteFactory::setRequest($request);

        //
        // Setup locale
        //
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
        View::share('locale', $locale);

        if ($user !== null && (string)$locale !== $user->locale) {
            $user->locale = (string)$locale;
            $user->save();
        }

        //
        // Setup timezone
        //
        if (null !== $user) {
            if (null === $user->timezone) {
                $user->timezone = config('app.timezone');
            }
            if (null !== $user->timezone) {
                date_default_timezone_set($user->timezone);
                config()->set('app.timezone', $user->timezone);
            }
        }

        //
        // Setup project
        //
        /** @var ProjectReference $projectReference */
        $projectReference = $request->route('project');
        if ($projectReference) {
            $this->projectReference = $projectReference;
        } else {
            $this->projectReference->setPort($request->getPort())->setHost($request->getHost());
        }
        $project = $this->projectRepository->findByReference($this->projectReference);
        View::share('project', $project);
        if (null === $project) {
            $request->merge(['project' => null]);
        } else {
            $request->merge(['project' => $this->projectReference]);
        }

        //
        // Setup active project settings
        //

        $config = $this->configRepository->acquire($project, 'app');
        View::share('config', $config);
        app()->singleton(Config::class, fn() => $config);

        return $next($request);
    }
}
