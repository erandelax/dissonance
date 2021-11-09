<?php

declare(strict_types=1);

namespace App\Components\Forms;

use App\Contracts\FormContract;
use App\Contracts\FormFieldContract;
use Illuminate\View\View;

final class FormFieldMarkdownPreview implements FormFieldContract
{
    private FormContract|null $form = null;

    public function __construct(
        private FormFieldContract $source,
        private string|null       $title = null,
        private string|null       $description = null,
        private bool              $useLabelColumn = true,
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

    public function getID(): string
    {
        return $this->source->getID() . '-preview-' . spl_object_id($this);
    }

    public function render(): View|string
    {
        return view("forms.fields.{$this->getStyle()}", [
            'field' => $this,
        ]);
    }

    public function hasErrors(): bool
    {
        return false;
    }

    public function isRequired(): bool
    {
        return false;
    }

    public function getTitle(): string
    {
        return $this->title ?? '';
    }

    public function getStyle(): string
    {
        return 'markdown-preview';
    }

    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    public function hasDescription(): bool
    {
        return null !== $this->description;
    }

    public function getValue(): mixed
    {
        return '';
    }

    public function setValue(mixed $value): self
    {
        return $this;
    }

    public function getName(): string
    {
        return '';
    }

    public function getSource(): FormFieldContract
    {
        return $this->source;
    }

    public function getMarkdownPreviewApiRoute(): string
    {
        return scoped_route('tools.markdown-preview');
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function isNullIgnored(): bool
    {
        return false;
    }
}
