<?php

declare(strict_types=1);

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self HTML()
 * @method static self BLADE()
 * @method static self MARKDOWN()
 * @method static self TEXT()
 * @see \App\Models\Raw::$type
 */
final class RawTypeEnum extends Enum
{
    protected static function values()
    {
        return [
            'HTML' => 'text/html',
            'BLADE' => 'script/blade',
            'MARKDOWN' => 'text/markdown',
            'TEXT' => 'text/plain',
        ];
    }
}
