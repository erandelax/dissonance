<?php

declare(strict_types=1);

namespace App\Entities;

use Illuminate\Contracts\Routing\UrlRoutable;

class Locale implements UrlRoutable
{
    public string $locale = 'en';

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    public function getRouteKey(): string
    {
        return $this->locale;
    }

    public function getRouteKeyName(): string
    {
        return 'locale';
    }

    public function resolveRouteBinding($value, $field = null): self
    {
        return tap(new self, fn (self $instance) => $instance->setLocale($value));
    }

    public function resolveChildRouteBinding($childType, $value, $field): void
    {
        throw new \Exception("Errrm, I haven't looked into how this is used, so I'm just gonna bail for now");
    }

    public function __toString()
    {
        return $this->locale;
    }
}
