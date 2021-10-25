<?php

declare(strict_types=1);

namespace App\Forms;

use App\Contracts\FormContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class QueryTable implements FormContract
{
    private array $columns = [];
    private array $batchActions = [];
    private array $actionCallbacks = [];

    public function __construct(
        private Builder      $query,
        private string|null  $id = null,
        private int          $pageSize = 10,
        private Request|null $request = null,
        private Notice|null  $notice = null,
        array                $columns = [],
        array                $batchActions = [],
        array                $actionCallbacks = [],
    )
    {
        foreach ($columns as $column) $this->addColumn($column);
        foreach ($batchActions as $batchAction) $this->addBatchAction($batchAction);
        foreach ($actionCallbacks as $action => $actionCallback) $this->setActionCallback($action, $actionCallback);
        if (null === $this->request) $this->request = request();
    }

    public function setActionCallback(string $action, callable $callback): self
    {
        $this->actionCallbacks[$action] = $callback;
        return $this;
    }

    public function setID(string|null $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getID(): string
    {
        return $this->id ?? 'table-' . spl_object_id($this);
    }

    public function setPageSize(int $pageSize): self
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    public function addBatchAction(BatchAction $batchAction): self
    {
        $this->batchActions[] = $batchAction;
        return $this;
    }

    public function getPaginator(): LengthAwarePaginator
    {
        return $this->query->paginate($this->pageSize);
    }

    public function setQuery(Builder $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function addColumn(ModelColumn $modelColumn): self
    {
        $this->columns[spl_object_id($modelColumn)] = $modelColumn;
        return $this;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getBatchActions(): array
    {
        return $this->batchActions;
    }

    public function render(): View|string
    {
        $this->processRequest();
        return view('forms.query_table', ['table' => $this]);
    }

    public function hasNotice(): bool
    {
        return null !== $this->notice;
    }

    public function getNotice(): Notice|null
    {
        return $this->notice;
    }

    private function processRequest(): void
    {
        $filters = $this->request->input('form-filter');
        /** @var \App\Forms\ModelColumn|null $column */
        foreach ($this->columns as $column) {
            if ($column && $column->hasFilter()) {
                $filter = $column->getFilter();
                $value = $filters[$filter->getID()] ?? null;
                $filter->setValue($value);
                if ($value) {
                    $filter->apply($value, $this->query);
                }
            }
        }
        if ($this->request->input('form') === $this->getID()) {
            $items = array_keys(array_filter($this->request->input('form-item', []), fn($value) => $value === 'on'));
            if ($action = $this->request->input('form-action')) {
                if (empty($items)) {
                    $this->notice = new Notice(
                        message: 'Action was not performed because no items are selected.',
                        style: Notice::STYLE_WARNING
                    );
                } else if (isset($this->actionCallbacks[$action])) {
                    $itemModels = (clone $this->query)->whereIn($this->query->getModel()->getKeyName(), $items)->get();
                    try {
                        $this->actionCallbacks[$action]($itemModels);
                        $this->notice = new Notice(
                            message: 'Action complete.',
                            style: Notice::STYLE_SUCCESS
                        );
                    } catch (FormError $error) {
                        $this->notice = new Notice(
                            message: $error->getMessage(),
                            style: Notice::STYLE_DANGER
                        );
                    }
                } else {
                    $this->notice = new Notice(
                        message: 'Action was not performed because no callbacks have been set.',
                        style: Notice::STYLE_WARNING
                    );
                }
            }
        }
    }
}
