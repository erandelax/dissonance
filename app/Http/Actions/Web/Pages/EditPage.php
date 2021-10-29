<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Pages;

use App\Entities\Locale;
use App\Entities\PageReference;
use App\Entities\ProjectReference;
use App\Http\Forms\Pages\PageForm;
use App\Http\Requests\StoreWikiRequest;
use App\Models\Page;
use App\Repositories\PageRepository;
use App\Repositories\PageRevisionRepository;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;

final class EditPage
{
    public function __construct(
        private PageRepository $pageRepository,
        private ProjectRepository $projectRepository,
    )
    {
    }

    public function __invoke(ProjectReference $project, Locale $locale, PageReference $page, Request $request)
    {
        $projectModel = $this->projectRepository->findByReference($project);
        $pageModel = $this->pageRepository->findByReference($projectModel, $page) ?? new Page;
        return view('web.pages.edit', [
            'form' => (new PageForm($pageModel))->submitRequest($request),
        ]);
    }
}
