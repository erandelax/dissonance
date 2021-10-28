<?php

declare(strict_types=1);

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * Original code based on the CommonMark JS reference parser (https://bitly.com/commonmark-js)
 *  - (c) John MacFarlane
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Services\Wiki\Markup\Parsers;

use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Parser\InlineParserContext;
use League\CommonMark\Parser\Inline;

final class AttributeModifierParser implements Inline\InlineParserInterface
{
    public function getMatchDefinition(): Inline\InlineParserMatch
    {
        return Inline\InlineParserMatch::regex('{:([^}]*)}');
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $inlineContext->getCursor()->advanceBy($inlineContext->getFullMatchLength());
        $matches = $inlineContext->getMatches();
        $attributes = $matches[1] ?? '';
        $attributes = ((array)(new \SimpleXMLElement("<element $attributes />"))->attributes())['@attributes']??[];
        $target = $inlineContext->getContainer()->lastChild() ?? $inlineContext->getContainer();
        if ($target instanceof Text) {
            $target = $target->parent();
        }
        if ($target) {
            $target->data->set('attributes', array_merge($target->data->get('attributes'), $attributes));
        }
        return true;
    }
}
