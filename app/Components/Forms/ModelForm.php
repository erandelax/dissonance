<?php

declare(strict_types=1);

namespace App\Components\Forms;

use App\Contracts\FormFieldContract;
use App\Utils\Alert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class ModelForm extends Form
{
    public function __construct(
        string $id,
        protected Model|null $model,
        string|null $action = null,
        string $method = 'post',
        bool $canSubmit = true,
        array $fields = [],
    ) {
        parent::__construct(id: $id, action: $action, method: $method, canSubmit: $canSubmit, fields: $fields);
    }

    public function render(): View|string
    {
        return view('forms.model_form', ['form' => $this]);
    }

    public function getModel(): Model|null
    {
        return $this->model;
    }

    public function addField(FormFieldContract|array|null $field, int|string $column = 0): self
    {
        $result = parent::addField($field, $column);
        if (null !== $this->model && $field instanceof FormField) {
            $field->setValue($this->getModelFieldValue($this->model, $field));
        }
        return $result;
    }

    public function submitRequest(Request $request): self
    {
        if (!$this->canSubmit) {
            return $this;
        }
        $data = array_merge(
            $request->file($this->getID(), []),
            $request->input($this->getID(), []),
        );
        $model = $this->getModel();
        if (null === $model) {
            return $this;
        }
        /** @var \App\Components\FormField $field */
        $hasErrors = false;
        foreach ($this->getFields() as $field) {
            if ($field->isReadOnly()) {
                continue;
            }
            $value = $data[$field->getID()] ?? null;
            if (null === $value && $field->getStyle() === FormField::STYLE_UPLOAD) {
                continue;
            }
            if ($field->setAndValidateValue($value)) {
                $this->setModelFieldValue($this->model, $field, $data[$field->getID()]);
            }
            if ($field->hasErrors()) {
                $hasErrors = true;
                (new Alert(content: 'Failure', alertType: Alert::TYPE_DANGER))->dispatch();
            }
        }
        if (!$hasErrors) {
            $model->save();
            (new Alert(content: 'Success', alertType: Alert::TYPE_SUCCESS))->dispatch();
        }
        return $this;
    }

    private function getModelFieldValue(Model|null $model, FormField $field): mixed
    {
        if (null === $model) {
            return null;
        }
        return Arr::get($model, $field->getAttribute());
    }

    private function setModelFieldValue(Model|null $model, FormField $field, mixed $value): self
    {
        $path = explode('.', $field->getAttribute(), 2);
        if (isset($path[1])) {
            $data = $model->{$path[0]};
            Arr::set($data, $path[1], $value);
            $model->{$path[0]} = $data;
        } else {
            Arr::set($model, $field->getAttribute(), $value);
        }

        return $this;
    }
}
