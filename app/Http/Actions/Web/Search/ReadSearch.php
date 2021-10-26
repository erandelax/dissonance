<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Search;

use App\Entities\Locale;

final class ReadSearch
{
    public function __invoke(Locale $locale)
    {
        return self::class;
    }
}
