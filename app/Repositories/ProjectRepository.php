<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\ProjectReference;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final class ProjectRepository
{
    public function paginate(): LengthAwarePaginator
    {
        return Project::paginate(10);
    }

    /**
     * @param \App\Entities\ProjectReference $reference
     *
     * @return \App\Models\Project|null
     */
    public function findByReference(ProjectReference $reference): Project|null
    {
        return Project::whereHost($reference->getHost())->where(static function(Builder $query) use($reference): void {
            $query->orWhere('port', $reference->getPort())->orWhereNull('port');
        })->orderByRaw('(port is not null) DESC')->first();
    }

    /**
     * @param string $host
     * @param int|null $port
     *
     * @return \App\Models\Project
     */
    public function createFromHostAndPort(string $host, int|null $port): Project
    {
        return Project::create([
            'host' => $host,
            'port' => $port,
        ]);
    }
}
