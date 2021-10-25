<?php

declare(strict_types=1);

namespace App\Helpers;

final class LocaleHelper
{
    public static function getOptions(): array
    {
        return array_combine(config('app.locales', []), config('app.locales', []));
    }
}
