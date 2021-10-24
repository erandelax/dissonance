<?php

declare(strict_types=1);

namespace App\Services\Wiki;

use App\Services\Wiki\Markup\MarkupConverter;
use Illuminate\Support\Str;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Node\Inline\Text;
use Ramsey\Uuid\Uuid;

final class MarkupRender
{
    public function __construct(
        private MarkupConverter $converter,
        private array $lastHeaders = [],
    ) {
        $this->converter->setDocumentMorpher([$this, 'morphDocument']);
    }

    public function getLastHeaders(): array
    {
        return $this->lastHeaders;
    }

    private function getInnerText($abstractBlock): string
    {
        /** @var AbstractBlock $result */
        $result = [];
        if ($abstractBlock instanceof Text) {
            $result[] = $abstractBlock->getLiteral();
        } else {
            foreach ($abstractBlock->children() as $child) {
                $result[] = $this->getInnerText($child);
            }
        }
        return implode('', $result);
    }

    public function morphDocument(Document $document): void
    {
        $this->lastHeaders = [];

        $counts = [];
        $children = $document->children();
        foreach ($children as $element) {
            if ($element instanceof Heading) {
                $text = $this->getInnerText($element);
                $id = Str::slug($text);
                isset($counts[$id]) ?: $counts[$id] = 0;
                if (++$counts[$id] > 1) {
                    $id .= '-' . $counts[$id];
                }
                $element->data->set('attributes', [
                    'id' => $id,
                ]);
                $this->lastHeaders[$id] = [
                    'id' => $id,
                    'header' => $text,
                    'level' => $element->getLevel(),
                ];
            }
        }
    }

    public function toHtml(string $markup): string
    {
        $result = $this->converter->convertToHtml($markup);
        $document = $result->getDocument();
        foreach ($document->children() as $child) {
            if ($child instanceof Heading) {
                $child->data->set('id', Uuid::uuid4());
            }
        }

        return (string) $result;
    }
}
