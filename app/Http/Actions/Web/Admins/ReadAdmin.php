<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins;

use App\Entities\Locale;
use App\Entities\PageReference;
use App\Entities\ProjectReference;
use App\Http\Actions\Web\Admins\Platform;
use App\Http\Actions\Web\Admins\Project;
use App\Models\Config;

final class ReadAdmin
{
    private const PAGES = [
        'settings' => [Platform\AdminSettings::class, 'read'],
        'projects' => [Platform\BrowseReadAndEditProjects::class, 'browse'],
        'users' => [Platform\BrowseReadAndEditUsers::class, 'browse'],
        'pages' => [Platform\ReadPages::class, 'browse'],
        'posts' => [Platform\ReadPosts::class, 'browse'],
        'uploads' => [Platform\BrowseAndReadUploads::class, 'browse'],
        'project-settings' => [Project\ReadSettings::class, 'read'],
        'project-users' => [Project\ReadUsers::class, 'browse'],
        'project-pages' => [Project\ReadPages::class, 'browse'],
        'project-posts' => [Project\ReadPosts::class, 'browse'],
        'project-uploads' => [Project\ReadUploads::class, 'browse'],
    ];

    public function __invoke(ProjectReference $project, Locale $locale, PageReference $page, Config $config, string|null $id = null)
    {
        $callable = self::PAGES[(string)$page] ?? null;
        if (null === $callable) {
            abort(404);
        }
        $callable[0] = app()->make($callable[0]);
        if ($id !== null) {
            $callable[1] = 'read';
        }
        if (request()->method() === 'POST') {
            $callable[1] = 'edit';
        }
        return app()->call($callable, [
            'project' => $project,
            'locale' => $locale,
            'page' => $page,
            'config' => $config,
            'id' => $id,
        ]);
    }
}
