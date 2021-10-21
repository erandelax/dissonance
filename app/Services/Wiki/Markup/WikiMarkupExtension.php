<?php

declare(strict_types=1);

namespace App\Services\Wiki\Markup;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\DisallowedRawHtml\DisallowedRawHtmlExtension;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;

final class WikiMarkupExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        //$environment->addInlineParser(new WikiLinkParser(), 300);
        $environment->addExtension(new AutolinkExtension());
        $environment->addExtension(new DisallowedRawHtmlExtension());
        $environment->addExtension(new StrikethroughExtension());
        $environment->addExtension(new TableExtension());
        $environment->addExtension(new TaskListExtension());
    }
}
