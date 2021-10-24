<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Entities;

use App\Entities\LocaleReference;

final class BrowseEntities
{
    public function __invoke(LocaleReference $locale)
    {
        return self::class;
    }
}
