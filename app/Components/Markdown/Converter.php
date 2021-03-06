<?php

declare(strict_types=1);

namespace App\Components\Markdown;

use League\CommonMark\Environment\Environment;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Output\RenderedContentInterface;


final class Converter extends MarkdownConverter
{
    /** @var callable|null */
    private mixed $documentMorpher = null;

    public function setDocumentMorpher(mixed $callable): void
    {
        $this->documentMorpher = $callable;
    }

    /**
     * Create a new Markdown converter pre-configured for GFM
     *
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        $environment = new Environment($config);
        $environment->addExtension(new Schema());
        parent::__construct($environment);
    }

    public function convertToHtml(string $markdown): RenderedContentInterface
    {
        $documentAST = $this->markdownParser->parse($markdown);
        $this->morphDocument($documentAST);
        return $this->htmlRenderer->renderDocument($documentAST);
    }

    private function morphDocument(Document $document): void
    {
        if (null !== $this->documentMorpher) {
            ($this->documentMorpher)($document);
        }
    }

    public function getEnvironment(): Environment
    {
        \assert($this->environment instanceof Environment);
        return $this->environment;
    }
}
