<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Uploads;

use App\Entities\Locale;
use App\Entities\ProjectReference;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class BrowseUploads
{
    public function __invoke(ProjectReference $project, Locale $locale, Request $request): View
    {
        return view('web.uploads.browse', [
            'uploads' => Upload::orderBy('created_at','desc')->paginate(21)->appends($request->except('page')),
        ]);
    }
}
