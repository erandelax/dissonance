<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Entities;

use App\Entities\Locale;

final class BrowseEntities
{
    public function __invoke(Locale $locale)
    {
        return self::class;
    }
}
