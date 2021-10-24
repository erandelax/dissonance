<?php

declare(strict_types=1);

namespace App\Entities;

use Illuminate\Contracts\Routing\UrlRoutable;

class EntityReference implements UrlRoutable
{
    public string $entity;

    public function setEntity(string $entity): self
    {
        $this->entity = $entity;
        return $this;
    }

    public function getRouteKey(): string
    {
        return $this->entity;
    }

    public function getRouteKeyName(): string
    {
        return 'locale';
    }

    public function resolveRouteBinding($value, $field = null): self
    {
        return tap(new self, fn (self $instance) => $instance->setEntity($value));
    }

    public function resolveChildRouteBinding($childType, $value, $field): void
    {
        throw new \Exception("Errrm, I haven't looked into how this is used, so I'm just gonna bail for now");
    }

    public function __toString()
    {
        return $this->entity;
    }
}
