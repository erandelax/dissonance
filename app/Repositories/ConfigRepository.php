<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Config;
use App\Models\Project;

final class ConfigRepository
{
    public function acquire(
        Project|null $project,
        string $type,
    ): Config {
        return Config::firstOrCreate([
            'project_id' =>  $project?->getKey(),
            'type' => $type,
        ], [
            'data' => [],
        ]);
    }
}
