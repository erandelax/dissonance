<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Search;

use App\Entities\LocaleReference;

final class ReadSearch
{
    public function __invoke(LocaleReference $locale)
    {
        return self::class;
    }
}
