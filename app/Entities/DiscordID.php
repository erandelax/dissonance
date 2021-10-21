<?php

declare(strict_types=1);

namespace App\Entities;

use Illuminate\Contracts\Routing\UrlRoutable;

class DiscordID implements UrlRoutable
{
    public string $username = '';

    public function getRouteKey(): string
    {
        return $this->username;
    }

    public function getRouteKeyName(): string
    {
        return 'username';
    }

    public function resolveRouteBinding($value, $field = null): self
    {
        return tap(new self, fn (self $instance) => $instance->username = $value);
    }

    public function resolveChildRouteBinding($childType, $value, $field): void
    {
        throw new \Exception("Not supported.");
    }

    public function __toString()
    {
        return $this->username;
    }
}
