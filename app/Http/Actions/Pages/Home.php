<?php

declare(strict_types=1);

namespace App\Http\Actions\Pages;

use App\Http\Actions\Action;
use Spatie\RouteAttributes\Attributes\Get;

final class Home extends Action
{
    #[Get(uri: '/{locale?}', name: 'home', middleware: 'web')]
    public function __invoke(): \Illuminate\Contracts\View\View
    {
        return view('pages.home');
    }
}
