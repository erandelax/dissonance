<?php

declare(strict_types=1);

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Services\Wiki\Markup;

use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

final class WikiLinkParser implements InlineParserInterface
{
    private const REGEX = "\\[{2}[^\\]]+\\]{2}";

    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex(self::REGEX);
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $link = substr($inlineContext->getFullMatch(), 2, -2);
        $parts = explode('|', $link, 2);
        $inlineContext->getCursor()->advanceBy(\strlen($link) + 4);
        $inlineContext->getContainer()->appendChild(new Link($parts[0], $parts[1] ?? $parts[0]));

        return true;
    }
}
