<?php

declare(strict_types=1);

namespace App\Http\Actions\Wiki;

use App\Entities\Locale;
use App\Entities\Wiki\Page\Slug;
use App\Http\Actions\Action;
use App\Models\PageRevision;
use App\Repositories\PageRevisionRepository;
use App\Repositories\Wiki\PageRepository;
use App\Services\Wiki\MarkupRender;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Jfcherng\Diff\Differ;
use Jfcherng\Diff\Factory\RendererFactory;
use Jfcherng\Diff\Renderer\RendererConstant;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Where;

final class ViewPage extends Action
{
    public function __construct(
        private PageRepository $pageRepository,
        private PageRevisionRepository $pageRevisionRepository,
    ) {}

    #[Get(uri: '/{locale}/wiki/{slug?}', name: 'wiki', middleware: 'web')]
    #[Where('slug','.*')]
    public function __invoke(Request $request, Locale $locale, Slug $slug, MarkupRender $markupRender): View
    {
        $mode = $request->get('mode');
        $view = 'wiki.page';
        $page = $this->pageRepository->findBySlug($locale, $slug) ?? $this->pageRepository->make404($slug);
        if (Gate::allows('update-page', $page)) {
            if ('edit' === $mode) {
                $view = 'wiki.page_editor';
            }

            if ('restore' === $mode && $revisionID = $request->get('revision')) {
                /** @var PageRevision $revision */
                $revision = $this->pageRevisionRepository->findByID($revisionID);
                $from = $page->content;
                $to = $revision->data['content'] ?? $page->content;
                $diff = $this->getDifference($from, $to);
                if (null !== $revision) {
                    return view('wiki.page_diff', [
                        'page' => $page->fill($revision->data),
                        'revision' => $revision,
                        'diff' => $diff,
                        'html' =>  $markupRender->toHtml($page->content ?? ''),
                        'errors' => null,
                    ]);
                }
            }
        }

        return view($view, [
            'page' => $page,
            'html' => $markupRender->toHtml($page->content ?? ''),
            'errors' => null,
        ]);
    }

    private function getDifference(string $from, string $to): string
    {
        $differ = new Differ(explode("\n", $from), explode("\n", $to), [
            // show how many neighbor lines
            // Differ::CONTEXT_ALL can be used to show the whole file
            'context' => 3,
            // ignore case difference
            'ignoreCase' => false,
            // ignore whitespace difference
            'ignoreWhitespace' => false,
        ]);

        // renderer class name:
        //     Text renderers: Context, JsonText, Unified
        //     HTML renderers: Combined, Inline, JsonHtml, SideBySide
        $renderer = RendererFactory::make('Combined',  [
            // how detailed the rendered HTML in-line diff is? (none, line, word, char)
            'detailLevel' => 'line',
            // renderer language: eng, cht, chs, jpn, ...
            // or an array which has the same keys with a language file
            'language' => 'eng',
            // show line numbers in HTML renderers
            'lineNumbers' => true,
            // show a separator between different diff hunks in HTML renderers
            'separateBlock' => false,
            // show the (table) header
            'showHeader' => false,
            // the frontend HTML could use CSS "white-space: pre;" to visualize consecutive whitespaces
            // but if you want to visualize them in the backend with "&nbsp;", you can set this to true
            'spacesToNbsp' => true,
            // HTML renderer tab width (negative = do not convert into spaces)
            'tabSize' => 2,
            // this option is currently only for the Combined renderer.
            // it determines whether a replace-type block should be merged or not
            // depending on the content changed ratio, which values between 0 and 1.
            'mergeThreshold' => 0.8,
            // this option is currently only for the Unified and the Context renderers.
            // RendererConstant::CLI_COLOR_AUTO = colorize the output if possible (default)
            // RendererConstant::CLI_COLOR_ENABLE = force to colorize the output
            // RendererConstant::CLI_COLOR_DISABLE = force not to colorize the output
            'cliColorization' => RendererConstant::CLI_COLOR_AUTO,
            // this option is currently only for the Json renderer.
            // internally, ops (tags) are all int type but this is not good for human reading.
            // set this to "true" to convert them into string form before outputting.
            'outputTagAsString' => false,
            // this option is currently only for the Json renderer.
            // it controls how the output JSON is formatted.
            // see available options on https://www.php.net/manual/en/function.json-encode.php
            'jsonEncodeFlags' => \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE,
            // this option is currently effective when the "detailLevel" is "word"
            // characters listed in this array can be used to make diff segments into a whole
            // for example, making "<del>good</del>-<del>looking</del>" into "<del>good-looking</del>"
            // this should bring better readability but set this to empty array if you do not want it
            'wordGlues' => [' ', '-'],
            // change this value to a string as the returned diff if the two input strings are identical
            'resultForIdenticals' => $to,
            // extra HTML classes added to the DOM of the diff container
            'wrapperClasses' => ['diff-wrapper'],
        ]);

        return $renderer->render($differ);
    }
}
