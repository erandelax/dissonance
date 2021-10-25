<?php

declare(strict_types=1);

namespace App\Forms;

use App\Contracts\FormContract;

class FormAction
{
    public function __construct(
        private string $title,
        private string|FormModal $action,
        private FormContract|null $forForm = null,
    ) {
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
