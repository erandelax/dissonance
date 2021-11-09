<?php

declare(strict_types=1);

namespace App\Contracts;

interface FormFieldContract extends FormContract
{
    public function setForm(FormContract|null $form): self;
    public function getForm(): FormContract|null;
    public function hasErrors(): bool;
    public function isRequired(): bool;
    public function getTitle(): string;
    //public function getStyle(): string;
    public function getDescription(): string;
    public function hasDescription(): bool;
    public function getValue(): mixed;
    public function setValue(mixed $value): self;
    public function getName(): string;
    public function isReadOnly(): bool;
    public function hasLabelColumn(): bool;
    public function isNullIgnored(): bool;
}
