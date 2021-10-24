<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins;

use App\Entities\LocaleReference;

final class BrowseAdmins
{
    public function __invoke(LocaleReference $locale)
    {
        return self::class;
    }
}
