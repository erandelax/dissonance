<?php

declare(strict_types=1);

namespace App\Repositories\Wiki;

use App\Entities\Locale;
use App\Entities\Wiki\Page\Slug;
use App\Models\WikiPage;

class PageRepository
{
    public function make404(Slug $slug): WikiPage
    {
        return WikiPage::make([
            'slug' => (string)$slug,
            'title' => 'Page not found',
            'locale' => app()->getLocale(),
            'content' => 'Seems like this page does not exist it. Want to create it?',
        ]);
    }

    public function findBySlug(Locale $locale, Slug $slug): ?WikiPage
    {
        return WikiPage::whereLocale($locale)->whereSlug($slug)->first();
    }
}
