<?php

declare(strict_types=1);

namespace App\Services\Wiki;

use App\Services\Wiki\Markup\MarkupConverter;

final class MarkupRender
{
    public function __construct(
        private MarkupConverter $converter
    ) {}

    public function toHtml(string $markup): string
    {
        return (string) $this->converter->convertToHtml($markup);
    }
}
