<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Entities;

use App\Entities\EntityReference;
use App\Entities\LocaleReference;

final class ReadEntity
{
    public function __invoke(LocaleReference $locale, EntityReference $entity)
    {
        return self::class;
    }
}
