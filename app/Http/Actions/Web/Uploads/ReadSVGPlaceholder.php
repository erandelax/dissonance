<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Uploads;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ReadSVGPlaceholder
{
    public function __invoke(Request $request): Response
    {
        $value = $request->get('value', 'N/A');
        return response(
            view('svg.placeholder', [
                'value' => (string)$value
            ])
        )->header('Content-Type', 'image/svg+xml');
    }
}
