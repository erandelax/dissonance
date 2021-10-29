<?php

declare(strict_types=1);

namespace App\Components\Tables;

use App\Contracts\FormFilterContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class QueryFilter implements FormFilterContract
{
    public const STYLE_TEXT = 'input';
    public const STYLE_DATE = 'date';
    public const STYLE_OPTIONS = 'options';

    public const TYPE_EQUALS = '=';
    public const TYPE_LIKE = 'like';

    private mixed $callback = null;
    private array $errors = [];

    public function __construct(
        private string      $style = self::STYLE_TEXT,
        private string      $type = self::TYPE_EQUALS,
        private string|null $id = null,
        private array       $options = [],
        private mixed       $value = null,
        callable|null       $callback = null,
        private array       $rules = [],
    )
    {
        $this->callback = $callback;
    }

    public function setValue(mixed $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getStyle(): string
    {
        return $this->style;
    }

    public function render(): View|string
    {
        return view('forms.query_filter', ['filter' => $this]);
    }

    public function getID(): string
    {
        return $this->id ?? 'filter-' . spl_object_id($this);
    }

    public function validate(mixed $input): bool
    {
        $validator = Validator::make(['value' => $input], ['value' => $this->rules]);
        if ($validator->fails()) {
            $this->errors = $validator->getMessageBag()->all();
            return false;
        }
        return true;
    }

    public function apply(mixed $input, Builder $query): void
    {
        if ($this->validate($input)) {
            if ($this->callback) {
                ($this->callback)($input, $query);
            } else {
                switch ($this->type) {
                    case self::TYPE_EQUALS:
                        if ($this->style === self::STYLE_DATE) {
                            $date = Carbon::parse($input);
                            $from = (clone $date)->setTime(0,0,0);
                            $to = (clone $from)->addDay();
                            $query
                                ->where($this->id, '>=', $from)
                                ->where($this->id, '<', $to);
                        } else {
                            $query->where($this->id, '=', $input);
                        }
                        break;
                    case self::TYPE_LIKE:
                        $query->where($this->id, 'like', "%$input%");
                        break;
                }
            }
        }
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
