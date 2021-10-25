<?php

declare(strict_types=1);

namespace App\Forms;

use Illuminate\Database\Eloquent\Model;

final class ModelColumn
{
    public function __construct(
        private string           $attribute,
        private string|null      $title = null,
        private QueryFilter|null $filter = null,
    ){
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
        return $model->{$this->attribute} ?? 'N/A';
    }
}
