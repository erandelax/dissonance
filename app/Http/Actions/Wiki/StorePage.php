<?php

declare(strict_types=1);

namespace App\Http\Actions\Wiki;

use App\Entities\Locale;
use App\Entities\Wiki\Page\Slug;
use App\Http\Actions\Action;
use App\Http\Requests\StoreWikiRequest;
use App\Models\Page;
use App\Repositories\PageRevisionRepository;
use App\Repositories\Wiki\PageRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Where;

class StorePage extends Action
{
    public function __construct(
        private PageRepository $pageRepository,
        private PageRevisionRepository $pageRevisionRepository,
    )
    {
    }

    #[Post(uri: '/{locale}/wiki/{slug?}', name: 'wiki.store', middleware: 'web')]
    #[Where('slug', '.*')]
    public function __invoke(StoreWikiRequest $request, Locale $locale, Slug $slug): View|RedirectResponse
    {
        $data = $request->validated();
        $page = $this->pageRepository->findBySlug($locale, $slug);
        // Partially restore from some revision
        if (isset($data['revision'])) {
            $revision = $this->pageRevisionRepository->findByID($data['revision']);
            unset($data['revision']);
            $data = array_merge($revision->data, $data);
        }
        // Map data
        $modelData = [
            'slug' => $data['slug'] ?? (string)$slug,
            'title' => $data['title'],
            'content' => $data['content'],
            'locale' => $data['locale'] ?? (string)$locale,
        ];
        // Store
        if (null === $page) {
            $page = Page::create($modelData);
        } else {
            $page->update($modelData);
        }

        return redirect(route('wiki', ['locale' => $locale, 'slug' => $page->slug]));
    }
}
