<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Projects;

use App\Entities\ProjectReference;
use App\Repositories\ProjectRepository;

final class ReadProject
{
    public function __construct(
        private ProjectRepository $projectRepository
    )
    {
    }

    public function __invoke(ProjectReference $project)
    {
        $projectModel = $this->projectRepository->findByReference($project);
        if (null === $projectModel) {
            return  "Can't find project {$project}";
        }
        return view('web.projects.read', [
            'project' => $projectModel,
        ]);
    }
}
