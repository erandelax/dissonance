<?php

declare(strict_types=1);

namespace App\Components\Forms;

use App\Contracts\FormContract;
use App\Contracts\FormFieldContract;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

final class FormField implements FormFieldContract
{
    public const STYLE_TEXT = 'text';
    public const STYLE_TEXTAREA = 'textarea';
    public const STYLE_UPLOAD = 'upload';
    public const STYLE_SELECT = 'select';
    public const STYLE_MARKDOWN = 'markdown';

    private FormContract|null $form = null;

    public function __construct(
        private string      $attribute,
        private string      $style = self::STYLE_TEXT,
        private string|null $title = null,
        private string|null $description = null,
        private array       $options = [],
        private array       $errors = [],
        private array       $rules = [],
        private bool        $readOnly = false,
        private mixed       $value = null,
        private bool        $useLabelColumn = true,
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

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getStyle(): string
    {
        return $this->style;
    }

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
        return view("forms.fields.{$this->style}", [
            'field' => $this,
        ]);
    }

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
}
