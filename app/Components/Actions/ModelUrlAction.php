<?php

declare(strict_types=1);

namespace App\Components\Actions;

use Illuminate\Database\Eloquent\Model;

final class ModelUrlAction extends ModelAction
{
    private mixed $urlMaker;

    public function __construct(string $title, callable $urlMaker, string $style = self::STYLE_DEFAULT)
    {
        $this->urlMaker = $urlMaker;
        parent::__construct(title: $title, action: 'url', forForm: null, style: $style);
    }

    public function makeURL(Model $model): string
    {
        return (string)($this->urlMaker)($model);
    }
}
