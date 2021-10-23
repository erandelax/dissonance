<?php

declare(strict_types=1);

namespace App\Http\Actions\Search;

use App\Entities\Locale;
use App\Http\Actions\Action;
use Spatie\RouteAttributes\Attributes\Get;

final class Search extends Action
{
    #[Get(uri: '/{locale}/search', name: 'search', middleware: 'web')]
    public function __invoke(Locale $locale): \Illuminate\Contracts\View\View
    {
        return view('search', [
            'query' => request()->get('q'),
        ]);
    }
}
