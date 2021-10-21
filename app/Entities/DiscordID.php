<?php

declare(strict_types=1);

namespace App\Entities;

use Illuminate\Contracts\Routing\UrlRoutable;

class DiscordID implements UrlRoutable
{
    public string $discordID = '';

    public function getRouteKey(): string
    {
        return $this->discordID;
    }

    public function getRouteKeyName(): string
    {
        return 'discordID';
    }

    public function resolveRouteBinding($value, $field = null): self
    {
        return tap(new self, fn (self $instance) => $instance->discordID = $value);
    }

    public function resolveChildRouteBinding($childType, $value, $field): void
    {
        throw new \Exception("Not supported.");
    }

    public function __toString()
    {
        return $this->discordID;
    }
}
