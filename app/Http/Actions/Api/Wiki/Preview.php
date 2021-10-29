<?php

declare(strict_types=1);

namespace App\Http\Actions\Api\Wiki;

use App\Http\Actions\Action;
use App\Services\Markdown\MarkupRender;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Post;

final class Preview extends Action
{
    #[Post('api/wiki/preview', name: 'api:wiki.preview')]
    public function __invoke(MarkupRender $markupRender, Request $request)
    {
        return response()->json([
            'content' => $markupRender->toHtml($request->input('content','') ?? ''),
        ]);
    }
}
