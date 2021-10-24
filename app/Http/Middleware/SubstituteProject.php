<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Entities\ProjectReference;
use App\Factories\ScopedRouteFactory;
use App\Repositories\ProjectRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

final class SubstituteProject
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ProjectReference $projectReference,
    ) {}

    public function handle(Request $request, \Closure $next)
    {
        /** @var ProjectReference $projectReference */
        if ($projectReference = $request->route('project')) {
            $this->projectReference = $projectReference;
        } else {
            $this->projectReference->setPort($request->getPort())->setHost($request->getHost());
        }
        $project = $this->projectRepository->findByReference($this->projectReference);
        View::share('project', $project);
        if (null === $project) {
            $request->merge(['project' => null]);
        } else {
            $request->merge(['project' => $this->projectReference]);
        }
        ScopedRouteFactory::setRequest($request);
        return $next($request);
    }
}
