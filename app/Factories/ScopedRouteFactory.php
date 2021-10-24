<?php

declare(strict_types=1);

namespace App\Factories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Psr\Log\LoggerInterface;

final class ScopedRouteFactory
{
    private static Request|null $currentRequest = null;

    public const SCOPE_ROOT = 'root';
    public const SCOPE_SUBDOMAIN = 'subdomain';
    public const SCOPE_SUBDIRECTORY = 'subdirectory';

    /**
     * @param \Illuminate\Http\Request|null $request
     */
    public static function setRequest(Request|null $request): void
    {
        self::$currentRequest = $request;
    }

    public static function make($name, $parameters = [], $absolute = true)
    {
        $url = app('url');
        /** @var \App\Entities\ProjectReference|null $project */
        $project = self::$currentRequest->input('project');
        if (null === $project) {
            return $url->route(self::SCOPE_ROOT . ':' . $name, $parameters, $absolute);
        }
        $projectName = self::SCOPE_SUBDIRECTORY . ':' . $name;
        if (
            (self::$currentRequest->getHost() === $project->getHost())
            && ($project->getPort() === null || self::$currentRequest->getPort() === $project->getPort())
        ) {
            $projectName = self::SCOPE_SUBDOMAIN . ':' . $name;
        }
        return Route::has($projectName)
            ? $url->route($projectName, array_merge(['project' => $project->getHost()], $parameters), $absolute)
            : $url->route(self::SCOPE_ROOT . ':' . $name, $parameters, $absolute);
    }
}
