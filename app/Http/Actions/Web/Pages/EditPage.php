<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Pages;

use App\Entities\Locale;
use App\Entities\PageReference;
use App\Entities\ProjectReference;
use App\Http\Requests\StoreWikiRequest;
use App\Models\Page;
use App\Repositories\PageRepository;
use App\Repositories\PageRevisionRepository;
use App\Repositories\ProjectRepository;

final class EditPage
{
    public function __construct(
        private PageRepository $pageRepository,
        private ProjectRepository $projectRepository,
        private PageRevisionRepository $pageRevisionRepository,
    )
    {
    }

    public function __invoke(ProjectReference $project, Locale $locale, PageReference $page, StoreWikiRequest $request)
    {
        $data = $request->validated();
        $projectModel = $this->projectRepository->findByReference($project);
        $pageModel = $this->pageRepository->findByReference($projectModel, $page);
        // Partially restore from some revision
        if (isset($data['revision'])) {
            $revision = $this->pageRevisionRepository->findByID($data['revision']);
            unset($data['revision']);
            $data = array_merge($revision->data, $data);
        }
        // Map data
        $modelData = [
            'project_id' => $projectModel?->getKey(),
            'slug' => $data['slug'] ?? (string)$page,
            'title' => $data['title'],
            'content' => $data['content'],
            'locale' => $data['locale'] ?? (string)$locale,
        ];
        // Store
        if (null === $pageModel) {
            $pageModel = Page::create($modelData);
        } else {
            $pageModel->update($modelData);
        }

        return redirect(scoped_route('pages.edit', ['locale' => $locale, 'page' => $pageModel->slug]));
    }
}
