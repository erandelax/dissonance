<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Entities;

use App\Entities\EntityReference;
use App\Entities\Locale;

final class ReadEntity
{
    public function __invoke(Locale $locale, EntityReference $entity)
    {
        return self::class;
    }
}
