<?php

declare(strict_types=1);

namespace App\Http\Actions\Wiki;

use App\Entities\Locale;
use App\Entities\Wiki\Page\Slug;
use App\Http\Actions\Action;
use App\Repositories\Wiki\PageRepository;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Where;

class StorePage extends Action
{
    public function __construct(
        private PageRepository $pageRepository
    ) {}

    #[Post(uri: '/{locale}/wiki/{slug?}', middleware: 'web')]
    #[Where('slug','.*')]
    public function __invoke(Request $request, Locale $locale, Slug $slug)
    {
        return view('wiki.page', [
            'page' => $this->pageRepository->findBySlug($locale, $slug),
        ]);
    }
}
