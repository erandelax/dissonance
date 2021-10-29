<?php

declare(strict_types=1);

namespace App\Components\Forms;

use App\Contracts\FormContract;

class FormAction
{
    public const STYLE_DEFAULT = '';
    public const STYLE_INFO = 'btn-primary';
    public const STYLE_SUCCESS = 'btn-success';
    public const STYLE_WARNING = 'btn-secondary';
    public const STYLE_DANGER = 'btn-danger';

    public function __construct(
        private string            $title,
        private string|FormModal  $action,
        private FormContract|null $forForm = null,
        private string            $style = self::STYLE_DEFAULT,
    )
    {
    }

    public function getStyle(): string
    {
        return $this->style;
    }

    public function isForForm(): bool
    {
        return null !== $this->forForm;
    }

    public function getForForm(): FormContract|null
    {
        return $this->forForm;
    }

    public function isCancel(): bool
    {
        return $this->action === 'cancel';
    }

    public function isModal(): bool
    {
        return $this->action instanceof FormModal;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAction(): string|FormModal
    {
        return $this->action;
    }
}
