<?php

declare(strict_types=1);

namespace App\Forms;

use App\Utils\Alert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ModelForm extends Form
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
        /** @var \App\Forms\ModelField $field */
        $hasErrors = false;
        foreach ($this->fields as $field) {
            if ($field->isReadOnly()) {
                continue;
            }
            $field->submitValue($model, $data[$field->getID()] ?? null);
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
}
