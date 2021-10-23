<?php

declare(strict_types=1);

namespace App\Repositories\Wiki;

use App\Entities\Locale;
use App\Entities\Wiki\Page\Slug;
use App\Models\Page;

class PageRepository
{
    public function make404(Slug $slug): Page
    {
        return Page::make([
            'slug' => (string)$slug,
            'title' => 'Page not found',
            'locale' => app()->getLocale(),
            'content' => 'Seems like this page does not exist it. Want to create it?',
        ]);
    }

    public function findBySlug(Locale $locale, Slug $slug): ?Page
    {
        return Page::whereLocale($locale)->whereSlug($slug)->first();
    }
}
