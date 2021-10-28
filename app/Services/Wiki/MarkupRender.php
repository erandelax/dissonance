<?php

declare(strict_types=1);

namespace App\Services\Wiki;

use App\Models\Upload;
use App\Repositories\PageRepository;
use App\Services\Wiki\Markup\MarkupConverter;
use Illuminate\Support\Str;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Node\Inline\Text;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

final class MarkupRender
{
    private string|null $projectID = null;
    private string|null $locale = null;

    public function __construct(
        private MarkupConverter $converter,
        private PageRepository $pageRepository,
        private array $lastHeaders = [],
    ) {
        $this->converter->setDocumentMorpher([$this, 'morphDocument']);
    }

    public function setLocale(string|null $locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    public function setProjectID(string|null $projectID): self
    {
        $this->projectID = $projectID;
        return $this;
    }

    public function getLastHeaders(): array
    {
        return $this->lastHeaders;
    }

    private function getInnerText($block): string
    {
        $result = [];
        if ($block instanceof Text) {
            $result[] = $block->getLiteral();
        } else {
            foreach ($block->children() as $child) {
                $result[] = $this->getInnerText($child);
            }
        }
        return implode('', $result);
    }

    private function getLinks($block): array
    {
        $result = [];
        if ($block instanceof Link) {
            $result[] = $block;
        } else {
            foreach ($block->children() as $child) {
                $result = array_merge($result, $this->getLinks($child));
            }
        }
        return $result;
    }

    private function getImages($block): array
    {
        $result = [];
        if ($block instanceof Image) {
            $result[] = $block;
        } else {
            foreach ($block->children() as $child) {
                $result = array_merge($result, $this->getImages($child));
            }
        }
        return $result;
    }

    public function morphDocument(Document $document): void
    {
        $this->lastHeaders = [];

        $counts = [];
        foreach ($document->children() as $element) {
            if ($element instanceof Heading) {
                $text = $this->getInnerText($element);
                $id = Str::slug($text);
                isset($counts[$id]) ?: $counts[$id] = 0;
                if (++$counts[$id] > 1) {
                    $id .= '-' . $counts[$id];
                }
                $element->data->set('attributes', array_merge($element->data->get('attributes'), [
                    'id' => $id,
                ]));
                $this->lastHeaders[$id] = [
                    'id' => $id,
                    'header' => $text,
                    'level' => $element->getLevel(),
                ];
            }
        }
        /** @var Link[] $links */
        $links = $this->getLinks($document);
        $slugs = array_map(static function(Link $link): string {
            return $link->getUrl();
        }, $links);
        $confirmedSlugs = $this->pageRepository->findSlugs($this->projectID, $this->locale, $slugs);
        foreach ($links as $link) {
            $url = $link->getUrl();
            if (isset($confirmedSlugs[$url])) {
                $link->data->set('attributes', array_merge($link->data->get('attributes'), [
                    'class' => 'ex',
                ]));
            } else {
                $link->data->set('attributes', array_merge($link->data->get('attributes'), [
                    'class' => 'nex',
                ]));
            }
            $link->setUrl(scoped_route('pages.read', [
                'page' => $url,
                'locale' => $this->locale ?? config('app.locale'),
            ]));
        }
        //
        $images = $this->getImages($document);
        /** @var Image $image */
        foreach ($images as $image) {
            $url = $image->getUrl();
            try {
                Uuid::fromString($url);
                $upload = Upload::find($url);
                $image->setUrl($upload->preview_url ?? $url);
            } catch (InvalidUuidStringException) {}
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
