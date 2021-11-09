<?php

declare(strict_types=1);

namespace App\Components\Forms\Fields;

use App\Components\Forms\FormField;

final class TextField extends FormField
{
    public function getViewName(): string
    {
        return 'forms.fields.text';
    }
}
