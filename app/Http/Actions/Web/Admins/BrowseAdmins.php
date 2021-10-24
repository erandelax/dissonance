<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins;

use App\Entities\LocaleReference;
use App\Entities\ProjectReference;

final class BrowseAdmins
{
    public function __invoke(ProjectReference $project, LocaleReference $locale)
    {
        return view('web.admins.browse');
    }
}
