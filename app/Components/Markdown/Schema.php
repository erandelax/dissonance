<?php

declare(strict_types=1);

namespace App\Components\Markdown;

use App\Components\Markdown\Extensions\Underline\UnderlineExtension;
use App\Components\Markdown\Parsers\AttributeModifierParser;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\DisallowedRawHtml\DisallowedRawHtmlExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use League\CommonMark\Extension\CommonMark\Delimiter\Processor\EmphasisDelimiterProcessor;
use League\CommonMark\Extension\ConfigurableExtensionInterface;
use League\CommonMark\Node as CoreNode;
use League\CommonMark\Parser as CoreParser;
use League\CommonMark\Renderer as CoreRenderer;
use League\Config\ConfigurationBuilderInterface;
use Nette\Schema\Expect;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark;

final class Schema implements ConfigurableExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addExtension(new SmartPunctExtension)
            ->addExtension(new AutolinkExtension())
            ->addExtension(new DisallowedRawHtmlExtension())
            ->addExtension(new StrikethroughExtension())
            ->addExtension(new TableExtension())
            ->addExtension(new TaskListExtension())
            ->addExtension(new UnderlineExtension())
            ->addBlockStartParser(new CommonMark\Parser\Block\BlockQuoteStartParser(), 70)
            ->addBlockStartParser(new CommonMark\Parser\Block\HeadingStartParser(), 60)
            ->addBlockStartParser(new CommonMark\Parser\Block\FencedCodeStartParser(), 50)
            ->addBlockStartParser(new CommonMark\Parser\Block\HtmlBlockStartParser(), 40)
            ->addBlockStartParser(new CommonMark\Parser\Block\ThematicBreakStartParser(), 20)
            ->addBlockStartParser(new CommonMark\Parser\Block\ListBlockStartParser(), 10)
            ->addBlockStartParser(new CommonMark\Parser\Block\IndentedCodeStartParser(), -100)
            ->addInlineParser(new AttributeModifierParser(), 300)
            ->addInlineParser(new CoreParser\Inline\NewlineParser(), 200)
            ->addInlineParser(new CommonMark\Parser\Inline\BacktickParser(), 150)
            ->addInlineParser(new CommonMark\Parser\Inline\EscapableParser(), 80)
            ->addInlineParser(new CommonMark\Parser\Inline\EntityParser(), 70)
            ->addInlineParser(new CommonMark\Parser\Inline\AutolinkParser(), 50)
            ->addInlineParser(new CommonMark\Parser\Inline\HtmlInlineParser(), 40)
            ->addInlineParser(new CommonMark\Parser\Inline\CloseBracketParser(), 30)
            ->addInlineParser(new CommonMark\Parser\Inline\OpenBracketParser(), 20)
            ->addInlineParser(new CommonMark\Parser\Inline\BangParser(), 10)
            ->addRenderer(CommonMark\Node\Block\BlockQuote::class, new CommonMark\Renderer\Block\BlockQuoteRenderer(), 0)
            ->addRenderer(CoreNode\Block\Document::class, new CoreRenderer\Block\DocumentRenderer(), 0)
            ->addRenderer(CommonMark\Node\Block\FencedCode::class, new CommonMark\Renderer\Block\FencedCodeRenderer(), 0)
            ->addRenderer(CommonMark\Node\Block\Heading::class, new CommonMark\Renderer\Block\HeadingRenderer(), 0)
            ->addRenderer(CommonMark\Node\Block\HtmlBlock::class, new CommonMark\Renderer\Block\HtmlBlockRenderer(), 0)
            ->addRenderer(CommonMark\Node\Block\IndentedCode::class, new CommonMark\Renderer\Block\IndentedCodeRenderer(), 0)
            ->addRenderer(CommonMark\Node\Block\ListBlock::class, new CommonMark\Renderer\Block\ListBlockRenderer(), 0)
            ->addRenderer(CommonMark\Node\Block\ListItem::class, new CommonMark\Renderer\Block\ListItemRenderer(), 0)
            ->addRenderer(CoreNode\Block\Paragraph::class, new CoreRenderer\Block\ParagraphRenderer(), 0)
            ->addRenderer(CommonMark\Node\Block\ThematicBreak::class, new CommonMark\Renderer\Block\ThematicBreakRenderer(), 0)
            ->addRenderer(CommonMark\Node\Inline\Code::class, new CommonMark\Renderer\Inline\CodeRenderer(), 0)
            ->addRenderer(CommonMark\Node\Inline\Emphasis::class, new CommonMark\Renderer\Inline\EmphasisRenderer(), 0)
            ->addRenderer(CommonMark\Node\Inline\HtmlInline::class, new CommonMark\Renderer\Inline\HtmlInlineRenderer(), 0)
            ->addRenderer(CommonMark\Node\Inline\Image::class, new CommonMark\Renderer\Inline\ImageRenderer(), 0)
            ->addRenderer(CommonMark\Node\Inline\Link::class, new CommonMark\Renderer\Inline\LinkRenderer(), 0)
            ->addRenderer(CoreNode\Inline\Newline::class, new CoreRenderer\Inline\NewlineRenderer(), 0)
            ->addRenderer(CommonMark\Node\Inline\Strong::class, new CommonMark\Renderer\Inline\StrongRenderer(), 0)
            ->addRenderer(CoreNode\Inline\Text::class, new CoreRenderer\Inline\TextRenderer(), 0);

        if ($environment->getConfiguration()->get('commonmark/use_asterisk')) {
            $environment->addDelimiterProcessor(new EmphasisDelimiterProcessor('*'));
        }

        if ($environment->getConfiguration()->get('commonmark/use_underscore')) {
            $environment->addDelimiterProcessor(new EmphasisDelimiterProcessor('_'));
        }
    }

    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        $builder->addSchema('commonmark', Expect::structure([
            'use_asterisk' => Expect::bool(true),
            'use_underscore' => Expect::bool(true),
            'enable_strong' => Expect::bool(true),
            'enable_em' => Expect::bool(true),
            'unordered_list_markers' => Expect::listOf('string')->min(1)->default(['*', '+', '-'])->mergeDefaults(false),
        ]));
    }
}
