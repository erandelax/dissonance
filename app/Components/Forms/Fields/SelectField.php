<?php

declare(strict_types=1);

namespace App\Components\Forms\Fields;

use App\Components\Forms\FormField;

final class SelectField extends FormField
{
    public function __construct(
        public array $options = [],
        ...$args
    )
    {
        parent::__construct(...$args);
    }

    public function getViewName(): string
    {
        return 'forms.fields.select';
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
