<?php

declare(strict_types=1);

namespace App\Components\Forms;

use App\Contracts\FormContract;
use App\Contracts\FormFieldContract;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

abstract class FormField implements FormFieldContract
{
    protected FormContract|null $form = null;

    public function __construct(
        private string      $attribute,
        protected string|null $title = null,
        protected string|null $description = null,
        protected array       $errors = [],
        protected array       $rules = [],
        protected bool        $readOnly = false,
        protected mixed       $value = null,
        protected bool        $useLabelColumn = true,
    )
    {
    }

    public function hasLabelColumn(): bool
    {
        return $this->useLabelColumn;
    }

    public function setForm(FormContract|null $form): self
    {
        $this->form = $form;
        return $this;
    }

    public function getForm(): FormContract|null
    {
        return $this->form;
    }

    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }


    /*public function getStyle(): string
    {
        return $this->style;
    }*/

    public function isRequired(): bool
    {
        return in_array('required', $this->rules);
    }

    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    public function hasDescription(): bool
    {
        return $this->description !== null;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function render(): View|string
    {
        return view($this->getViewName(), [
            'field' => $this,
        ]);
    }

    abstract public function getViewName(): string;

    public function getID(): string
    {
        return 'field-' . str_replace('.', '-', $this->attribute);
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }

    public function getTitle(): string
    {
        return $this->title ?? $this->attribute;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getName(): string
    {
        $form = $this->getForm();
        if (null === $form) {
            return $this->getID();
        }
        return "{$form->getID()}[{$this->getID()}]";
    }

    public function setAndValidateValue(mixed $value): bool
    {
        $validator = Validator::make([
            'value' => $value
        ], [
            'value' => $this->rules
        ]);
        if ($validator->fails()) {
            $this->errors = $validator->getMessageBag()->all();
            return false;
        }
        $this->setValue($value);
        return true;
    }

    public function isNullIgnored(): bool
    {
        return false;
    }
}
