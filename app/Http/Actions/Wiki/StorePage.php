<?php

declare(strict_types=1);

namespace App\Http\Actions\Wiki;

use App\Entities\Locale;
use App\Entities\Wiki\Page\Slug;
use App\Http\Actions\Action;
use App\Http\Requests\StoreWikiRequest;
use App\Models\WikiPage;
use App\Repositories\Wiki\PageRepository;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Where;

class StorePage extends Action
{
    public function __construct(
        private PageRepository $pageRepository
    )
    {
    }

    #[Post(uri: '/{locale}/wiki/{slug?}', name: 'wiki.store', middleware: 'web')]
    #[Where('slug', '.*')]
    public function __invoke(StoreWikiRequest $request, Locale $locale, Slug $slug)
    {
        $data = $request->validated();
        $page = $this->pageRepository->findBySlug($locale, $slug);
        $modelData = [
            'slug' => $data['slug'] ?? (string)$slug,
            'title' => $data['title'],
            'content' => $data['content'],
            'locale' => $data['locale'] ?? (string)$locale,
        ];
        if (null === $page) {
            $page = WikiPage::create($modelData);
        } else {
            $page->update($modelData);
        }

        return redirect(route('wiki', ['locale' => $locale, 'slug' => $page->slug]));
    }
}
