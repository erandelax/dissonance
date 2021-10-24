<?php

declare(strict_types=1);

namespace App\Entities;

use Illuminate\Contracts\Routing\UrlRoutable;

class PageReference implements UrlRoutable
{
    public string|null $page = null;

    public function setPage(string|null $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function getRouteKey(): string
    {
        return $this->page ?? '';
    }

    public function getRouteKeyName(): string
    {
        return 'locale';
    }

    public function resolveRouteBinding($value, $field = null): self
    {
        return tap(new self, fn (self $instance) => $instance->setPage($value));
    }

    public function resolveChildRouteBinding($childType, $value, $field): void
    {
        throw new \Exception("Errrm, I haven't looked into how this is used, so I'm just gonna bail for now");
    }

    public function __toString(): string
    {
        return $this->page ?? '';
    }
}
