<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins;

use App\Entities\Locale;
use App\Entities\ProjectReference;

final class BrowseAdmins
{
    public function __invoke(ProjectReference $project, Locale $locale)
    {
        return view('web.admins.browse');
    }
}
