<?php

declare(strict_types=1);

namespace App\Forms;

use App\Contracts\FormContract;
use Illuminate\View\View;

abstract class Form implements FormContract
{
    protected array $fields = [];

    public function __construct(
        protected string $id,
        protected string|null $action = null,
        protected string $method = 'post',
        protected bool $canSubmit = true,
        array $fields = [],
    ) {
        foreach ($fields as $field) $this->addField($field);
        if (null === $this->action) $this->action = url()->current();
    }

    public function hasSubmit(): bool
    {
        return $this->canSubmit;
    }

    public function getAction(): string|null
    {
        return $this->action;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function addField(ModelField|null $field): self
    {
        if (null === $field) {
            return $this;
        }
        $this->fields[spl_object_id($field)] = $field;
        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    abstract public function render(): View|string;

    public function getID(): string
    {
        return 'form-'.$this->id;
    }
}
