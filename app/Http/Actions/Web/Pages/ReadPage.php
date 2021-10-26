<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Pages;

use App\Entities\Locale;
use App\Entities\PageReference;
use App\Entities\ProjectReference;
use App\Factories\ScopedRouteFactory;
use App\Repositories\PageRepository;
use App\Repositories\PageRevisionRepository;
use App\Repositories\ProjectRepository;
use App\Services\Wiki\DiffRender;
use App\Services\Wiki\MarkupRender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class ReadPage
{
    public function __construct(
        private PageRevisionRepository $pageRevisionRepository,
        private ProjectRepository $projectRepository,
        private PageRepository $pageRepository,
        private MarkupRender $markupRender,
        private DiffRender $diffRender,
    ) {}

    public function __invoke(ProjectReference $project, Locale $locale, PageReference $page, Request $request)
    {
        $user = Auth::user();

        $mode = (string)$request->get('mode', 'read');

        $projectModel = $this->projectRepository->findByReference($project);
        $pageModel = $this->pageRepository->findByReference($projectModel, $page);
        $this->markupRender->setLocale((string)$locale)->setProjectID($projectModel?->getKey());

        if (null === $pageModel) {
            $pageModel = $this->pageRepository->make404($projectModel, $page);
        }

        if ($user?->can('update-page', $pageModel) && 'edit' === $mode) {
            return view('web.pages.edit', [
                'page' => $pageModel,
                'pageReference' => $page,
                'html' => $this->markupRender->toHtml($pageModel->content),
            ]);
        }

        if ($mode === 'restore' && $revisionID = $request->get('revision')) {
            $revision = $this->pageRevisionRepository->findByID($revisionID);
            if (null !== $revision) {
                $from = $pageModel->content;
                $to = $revision->data['content'] ?? $pageModel->content;
                $diff = $this->diffRender->diff($from, $to);
                return view('web.pages.restore', [
                    'page' => $pageModel,
                    'pageReference' => $page,
                    'html' => $this->markupRender->toHtml($to),
                    'revision' => $revision,
                    'diff' => $diff,
                    'headers' => $this->markupRender->getLastHeaders(),
                ]);
            }
        }

        return view('web.pages.read', [
            'page' => $pageModel,
            'pageReference' => $page,
            'html' => $this->markupRender->toHtml($pageModel->content),
            'headers' => $this->markupRender->getLastHeaders(),
        ]);
    }
}
