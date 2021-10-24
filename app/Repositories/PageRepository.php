<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\LocaleReference;
use App\Entities\PageReference;
use App\Models\Page;
use App\Models\Project;

final class PageRepository
{
    /**
     * @param \App\Models\Project|null $project
     * @param \App\Entities\PageReference $pageReference
     *
     * @return \App\Models\Page|null
     */
    public function findByReference(Project|null $project, PageReference $pageReference): ?Page
    {
        return Page::whereProjectId($project?->getKey())->whereSlug($pageReference)->first();
    }

    /**
     * @param string|null $projectID
     * @param string|null $locale
     * @param array $slugs
     *
     * @return array
     */
    public function findSlugs(string|null $projectID, string|null $locale, array $slugs): array
    {
        return Page::whereProjectId($projectID)
            ->whereLocale($locale)
            ->whereIn('slug', $slugs)
            ->pluck('slug', 'slug')
            ->all();
    }

    /**
     * @param \App\Models\Project|null $project
     * @param \App\Entities\PageReference $pageReference
     *
     * @return \App\Models\Page
     */
    public function make404(Project|null $project, PageReference $pageReference): Page
    {
        return Page::make([
            'title' => 'Page not found',
            'slug' => (string)$pageReference,
            'project_id' => $project?->getKey(),
            'locale' => app()->getLocale(),
            'content' => 'Want to create it?',
        ]);
    }

    /*public function make404(Slug $slug): Page
    {
        return Page::make([
            'slug' => (string)$slug,
            'title' => 'Page not found',
            'locale' => app()->getLocale(),
            'content' => Auth::guest() ? 'Seems like this page does not exist yet. Authorize if you want to change it.' : 'Seems like this page does not exist yet. Want to create it?',
        ]);
    }

    public function findBySlug(LocaleReference $locale, Slug $slug): ?Page
    {
        return Page::whereLocale($locale)->whereSlug($slug)->first();
    }*/
}
