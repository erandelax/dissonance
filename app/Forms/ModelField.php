<?php

declare(strict_types=1);

namespace App\Forms;

use App\Contracts\FormContract;
use App\Repositories\UploadRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

final class ModelField implements FormContract
{
    public const STYLE_TEXT = 'text';
    public const STYLE_IMAGE = 'image';
    public const STYLE_SELECT = 'select';

    public function __construct(
        private string $attribute,
        private string $style = self::STYLE_TEXT,
        private string|null $title = null,
        private string|null $description = null,
        private array $options = [],
        private array $errors = [],
        private array $rules = [],
    ) {}

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getStyle(): string
    {
        return $this->style;
    }

    public function isRequired(): bool
    {
        return in_array('required', $this->rules);
    }

    public function getDescription(): string|null
    {
        return $this->description;
    }

    public function hasDescription(): bool
    {
        return $this->description !== null;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function render(): View|string
    {
        return 'field';
    }

    public function getID(): string
    {
        return 'field-'. $this->attribute;
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }

    public function getTitle(): string
    {
        return $this->title ?? $this->attribute;
    }

    public function getValue(Model|null $model): mixed
    {
        if (null === $model) {
            return null;
        }
        return Arr::get($model, $this->attribute);
    }

    public function setValue(Model|null $model, mixed $value): self
    {
        $path = explode('.', $this->attribute, 2);
        if (isset($path[1])) {
            $data = $model->{$path[0]};
            Arr::set($data, $path[1], $value);
            $model->{$path[0]} = $data;
        } else {
            Arr::set($model, $this->attribute, $value);
        }

        return $this;
    }

    public function submitValue(Model|null $model, mixed $value): self
    {
        $validator = Validator::make(['value' => $value], ['value' => $this->rules]);
        if ($validator->fails()) {
            $this->errors = $validator->getMessageBag()->all();
        } else {
            $this->setValue($model, $value);
        }
        return $this;
    }

    public function isForFiles(): bool
    {
        return $this->style === self::STYLE_IMAGE;
    }
}
