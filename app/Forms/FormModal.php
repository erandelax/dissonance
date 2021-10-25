<?php

declare(strict_types=1);

namespace App\Forms;

use App\Contracts\FormContract;
use Illuminate\View\View;

final class FormModal implements FormContract
{
    private array $actions;

    public function __construct(
        private string|null $title = null,
        private string|View|FormContract|null $body = null,
        array $actions = [],
    ) {
        foreach ($actions as $action) $this->addAction($action);
    }

    public function getTitle(): string|null
    {
        return $this->title;
    }

    public function hasTitle(): bool
    {
        return null !== $this->title;
    }

    public function getBody(): string|View|FormContract|null
    {
        return $this->body;
    }

    public function hasBody(): bool
    {
        return null !== $this->body;
    }

    public function setTitle(string|null $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setBody(string|View|FormContract|null $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function addAction(FormAction $action)
    {
        $this->actions[spl_object_id($action)] = $action;
        return $this;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function getID(): string
    {
        return 'modal-'.spl_object_id($this);
    }

    public function render(): View|string
    {
        return view('forms.form_modal', ['modal' => $this]);
    }
}
