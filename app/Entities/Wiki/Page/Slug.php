<?php

declare(strict_types=1);

namespace App\Entities\Wiki\Page;

use Illuminate\Contracts\Routing\UrlRoutable;

class Slug implements UrlRoutable
{
    public string $slug = '';

    public function getRouteKey(): string
    {
        return $this->slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null): self
    {
        return tap(new self, fn (self $instance) => $instance->slug = $value);
    }

    public function resolveChildRouteBinding($childType, $value, $field): void
    {
        throw new \Exception("Not supported.");
    }

    public function __toString()
    {
        return $this->slug;
    }
}
