<?php

declare(strict_types=1);

namespace App\Components;

final class Notice
{
    public const STYLE_DEFAULT = '';
    public const STYLE_INFO = 'alert-primary';
    public const STYLE_SUCCESS = 'alert-success';
    public const STYLE_WARNING = 'alert-secondary';
    public const STYLE_DANGER = 'alert-danger';

    public function __construct(
        private string|null $title = null,
        private string|null $message = null,
        private bool        $dismissible = true,
        public string       $style = self::STYLE_DEFAULT,
    ) {
    }

    public function hasTitle(): bool
    {
        return null !== $this->title;
    }

    public function getTitle(): string|null
    {
        return $this->title;
    }

    public function hasMessage(): bool
    {
        return null !== $this->message;
    }

    public function getMessage(): string|null
    {
        return $this->message;
    }

    public function isDismissible(): bool
    {
        return $this->dismissible;
    }

    public function getStyle(): string
    {
        return $this->style;
    }
}
