<?php

declare(strict_types=1);

namespace App\Forms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

final class ModelColumn
{
    private array $modelActions = [];

    public function __construct(
        private string|null      $attribute = null,
        private string|null      $title = null,
        private QueryFilter|null $filter = null,
        array                    $actions = [],
    )
    {
        foreach ($actions as $action) $this->addModelAction($action);
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
