<?php

declare(strict_types=1);

namespace App\Services\Raw;

use App\Enums\RawTypeEnum;
use App\Models\Raw;
use App\Services\Markdown\MarkupRender;
use Illuminate\Support\Facades\Blade;

final class RawRender
{
    public function __construct(
        private MarkupRender $markupRender,
    )
    {
    }

    public function render(Raw $raw): string
    {
        $type = $raw->type;
        return match (true) {
            $type->equals(RawTypeEnum::HTML()) => $this->renderHTML($raw),
            $type->equals(RawTypeEnum::BLADE()) => $this->renderBlade($raw),
            $type->equals(RawTypeEnum::MARKDOWN()) => $this->renderMarkdown($raw),
            default => $raw->body ?? '',
        };
    }

    private function renderHTML(Raw $raw): string
    {
        return $raw->body ?? '';
    }

    private function renderBlade(Raw $raw): string
    {
        return Blade::compileString($raw->body ?? '');
    }

    private function renderMarkdown(Raw $raw): string
    {
        return $this->markupRender->toHtml($raw->body ?? '');
    }
}
