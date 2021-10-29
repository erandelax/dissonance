<?php

declare(strict_types=1);


namespace App\Components\Markdown\Parsers;

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
        try {
            $attributes = ((array)(new \SimpleXMLElement("<element $attributes />"))->attributes())['@attributes'] ?? [];
        } catch (\Exception) {
            $attributes = [];
        }
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
