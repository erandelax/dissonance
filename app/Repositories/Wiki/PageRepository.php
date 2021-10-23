<?php

declare(strict_types=1);

namespace App\Repositories\Wiki;

use App\Entities\Locale;
use App\Entities\Wiki\Page\Slug;
use App\Models\Page;
use Illuminate\Support\Facades\Gate;

class PageRepository
{
    public function make404(Slug $slug): Page
    {
        return Page::make([
            'slug' => (string)$slug,
            'title' => 'Page not found',
            'locale' => app()->getLocale(),
            'content' => Gate::check('update-page') ? 'Seems like this page does not exist yet. Want to create it?' : 'Seems like this page does not exist yet. Authorize if you want to change it.',
        ]);
    }

    public function findBySlug(Locale $locale, Slug $slug): ?Page
    {
        return Page::whereLocale($locale)->whereSlug($slug)->first();
    }
}
