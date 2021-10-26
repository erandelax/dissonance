<?php

declare(strict_types=1);

namespace App\Forms;

use App\Contracts\FormContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

class ModelForm implements FormContract
{
    private array $fields = [];

    public function __construct(
        private string $id,
        private Model|null $model,
        array $fields = [],
    ) {
        foreach ($fields as $field) $this->addField($field);
    }

    public function addField(ModelField $field): self
    {
        $this->fields[spl_object_id($field)] = $field;
        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function render(): View|string
    {
        return view('forms.model_form', ['form' => $this]);
    }

    public function getID(): string
    {
        return 'form-'.$this->id;
    }

    public function getModel(): Model|null
    {
        return $this->model;
    }
}
