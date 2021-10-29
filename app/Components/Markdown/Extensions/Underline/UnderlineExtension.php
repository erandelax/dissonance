<?php

declare(strict_types=1);

namespace App\Components\Markdown\Extensions\Underline;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

final class UnderlineExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addDelimiterProcessor(new UnderlineDelimiterProcessor());
        $environment->addRenderer(Underline::class, new UnderlineRenderer());
    }
}
