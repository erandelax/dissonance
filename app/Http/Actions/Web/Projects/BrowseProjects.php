<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Projects;

use App\Repositories\ProjectRepository;

final class BrowseProjects
{
    public function __construct(
        private ProjectRepository $projectRepository
    )
    {
    }

    public function __invoke()
    {
        return view('web.projects.browse', [
            'projects' => $this->projectRepository->paginate()
        ]);
    }
}
