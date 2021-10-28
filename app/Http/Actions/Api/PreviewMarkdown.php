<?php

declare(strict_types=1);

namespace App\Http\Actions\Api;

use App\Services\Wiki\MarkupRender;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class PreviewMarkdown
{
    /**
     * @param \App\Services\Wiki\MarkupRender $markupRender
     */
    public function __construct(
        private MarkupRender $markupRender,
    )
    {
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'content' => $this->markupRender->toHtml($request->input('content','') ?? ''),
        ]);
    }
}
