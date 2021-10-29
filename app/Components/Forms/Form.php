<?php

declare(strict_types=1);

namespace App\Components\Forms;

use App\Contracts\FormContract;
use App\Contracts\FormFieldContract;
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
        $column = 0;
        foreach ($fields as $field) {
            $this->addField(field: $field, column: $column);
            if (is_array($field)) {
                $column++;
            }
        }
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

    public function addField(FormFieldContract|array|null $field, int|string $column = 0): self
    {
        if (is_array($field)) {
            foreach ($field as $colField) {
                $this->addField(field: $colField, column: $column);
            }
            return $this;
        }
        if (null === $field) {
            return $this;
        }
        if (!isset($this->fields[$column])) {
            $this->fields[$column] = [];
        }
        $this->fields[$column][spl_object_id($field)] = $field;
        $field->setForm($this);
        return $this;
    }

    public function getFields(): array
    {
        return array_merge(...$this->fields);
    }

    public function getColumns(): array
    {
        return $this->fields;
    }

    abstract public function render(): View|string;

    public function getID(): string
    {
        return 'form-'.$this->id;
    }
}
