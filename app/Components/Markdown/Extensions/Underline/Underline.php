<?php

declare(strict_types=1);


namespace App\Components\Markdown\Extensions\Underline;

use League\CommonMark\Node\Inline\AbstractInline;
use League\CommonMark\Node\Inline\DelimitedInterface;

final class Underline extends AbstractInline implements DelimitedInterface
{
    private string $delimiter;

    public function __construct(string $delimiter = '__')
    {
        parent::__construct();

        $this->delimiter = $delimiter;
    }

    public function getOpeningDelimiter(): string
    {
        return $this->delimiter;
    }

    public function getClosingDelimiter(): string
    {
        return $this->delimiter;
    }
}
