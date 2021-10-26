<?php

declare(strict_types=1);

namespace App\Entities;

use App\Models\Project;
use App\Repositories\ProjectRepository;
use Illuminate\Contracts\Routing\UrlRoutable;

final class ProjectReference implements UrlRoutable
{
    private string|null $host = null;
    private int|null $port = null;

    public function __construct(
        private ProjectRepository $projectRepository,
    ) {
    }

    public function getModel(): Project|null
    {
        return $this->projectRepository->findByReference($this);
    }

    public function isRoot(): bool
    {
        return $this->host === null && $this->port === null;
    }

    public function isProject(): bool
    {
        return $this->host !== null || $this->port !== null;
    }

    public function setHost(string|null $host): self
    {
        $this->host = $host;
        return $this;
    }

    public function setPort(int|null $port): self
    {
        $this->port = $port;
        return $this;
    }

    public function getHost(): string|null
    {
        return $this->host;
    }

    public function getPort(): int|null
    {
        return $this->port;
    }

    public function getRouteKey(): string
    {
        return implode(':', array_filter([$this->host, $this->port]));
    }

    public function getRouteKeyName(): string
    {
        return 'project';
    }

    public function resolveRouteBinding($value, $field = null): self
    {
        return tap(new self(
            projectRepository: $this->projectRepository,
        ), function (self $instance) use($value) {
            $parts = explode(':', $value);
            if (!isset($parts[1])) $parts[1] = null;
            if ($parts[1]) $parts[1] = (int) $parts[1];
            if ($parts[1] === 80) $parts[1] = null;
            $instance->setHost($parts[0] ?? null)->setPort($parts[1] ?? null);
        });
    }

    public function resolveChildRouteBinding($childType, $value, $field): void
    {
        throw new \Exception("Errrm, I haven't looked into how this is used, so I'm just gonna bail for now");
    }

    public function __toString()
    {
        return "{$this->host}:{$this->port}";
    }
}
