<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins;

use App\Entities\LocaleReference;
use App\Entities\PageReference;
use App\Entities\ProjectReference;
use App\Http\Actions\Web\Admins\Platform;
use App\Http\Actions\Web\Admins\Project;

final class ReadAdmin
{
    private const PAGES = [
        'settings' => [Platform\ReadSettings::class, 'read'],
        'projects' => [Platform\ReadProjects::class, 'browse'],
        'users' => [Platform\ReadUsers::class, 'browse'],
        'pages' => [Platform\ReadPages::class, 'browse'],
        'posts' => [Platform\ReadPosts::class, 'browse'],
        'uploads' => [Platform\ReadUploads::class, 'browse'],
        'project-settings' => [Project\ReadSettings::class, 'read'],
        'project-users' => [Project\ReadUsers::class, 'browse'],
        'project-pages' => [Project\ReadPages::class, 'browse'],
        'project-posts' => [Project\ReadPosts::class, 'browse'],
        'project-uploads' => [Project\ReadUploads::class, 'browse'],
    ];

    public function __invoke(ProjectReference $project, LocaleReference $locale, PageReference $page, string|null $id = null)
    {
        $callable = self::PAGES[(string)$page] ?? null;
        if (null === $callable) {
            abort(404);
        }
        $callable[0] = app()->make($callable[0]);
        if ($id !== null) {
            $callable[1] = 'read';
        }
        return app()->call($callable, [
            'project' => $project,
            'locale' => $locale,
            'page' => $page,
            'id' => $id,
        ]);
    }
}
