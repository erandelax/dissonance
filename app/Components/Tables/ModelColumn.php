<?php

declare(strict_types=1);

namespace App\Components\Tables;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use App\Components\Actions\ModelAction;

final class ModelColumn
{
    private array $modelActions = [];

    public const STYLE_TEXT = 'text';
    public const STYLE_IMAGE = 'image';

    private mixed $style = null;

    public function __construct(
        private string|null      $attribute = null,
        private string|null      $title = null,
        private QueryFilter|null $filter = null,
        string|callable          $style = self::STYLE_TEXT,
        array                    $actions = [],
    )
    {
        foreach ($actions as $action) $this->addModelAction($action);
        $this->style = $style;
    }

    public function getStyle(): string|callable
    {
        return $this->style;
    }

    public function isStyleCallable(): bool
    {
        return $this->style instanceof \Closure;
    }

    public function hasModelActions(): bool
    {
        return !empty($this->modelActions);
    }

    public function getModelActions(): array
    {
        return $this->modelActions;
    }

    public function addModelAction(ModelAction $modelAction): self
    {
        $this->modelActions[spl_object_id($modelAction)] = $modelAction;
        return $this;
    }

    public function hasFilter(): bool
    {
        return null !== $this->filter;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function getTitle(): string
    {
        return $this->title ?? $this->attribute;
    }

    public function getValue(Model $model): mixed
    {
        return Arr::get($model, $this->attribute, 'N/A');
    }

    public function isAttribute(): bool
    {
        return $this->attribute !== null;
    }
}
