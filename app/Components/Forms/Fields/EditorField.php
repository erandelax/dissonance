<?php

declare(strict_types=1);

namespace App\Components\Forms\Fields;

use App\Components\Forms\FormField;

final class EditorField extends FormField
{
    public function getViewName(): string
    {
        return 'forms.fields.editor';
    }
}
