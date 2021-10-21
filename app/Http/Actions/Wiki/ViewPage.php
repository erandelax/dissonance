<?php

declare(strict_types=1);

namespace App\Http\Actions\Wiki;

use App\Entities\Locale;
use App\Entities\Wiki\Page\Slug;
use App\Http\Actions\Action;
use App\Repositories\Wiki\PageRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Where;

final class ViewPage extends Action
{
    public function __construct(
        private PageRepository $pageRepository
    ) {}

    #[Get(uri: '/{locale}/wiki/{slug?}', name: 'wiki', middleware: 'web')]
    #[Where('slug','.*')]
    public function __invoke(Request $request, Locale $locale, Slug $slug): View
    {
        $page = $this->pageRepository->findBySlug($locale, $slug) ?? $this->pageRepository->make404($slug);
        return view($page->getKey() ? 'wiki.page' : 'wiki.page_editor', [
            'page' => $page,
            'errors' => null,
        ]);
    }
}
