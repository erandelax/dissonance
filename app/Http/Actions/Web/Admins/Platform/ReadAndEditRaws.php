<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins\Platform;

use App\Components\Actions\BatchAction;
use App\Components\Actions\ModelAction;
use App\Components\Actions\ModelUrlAction;
use App\Components\Forms\FormAction;
use App\Components\Forms\FormField;
use App\Components\Forms\FormModal;
use App\Components\Forms\ModelForm;
use App\Components\Tables\ModelColumn;
use App\Components\Tables\QueryFilter;
use App\Components\Tables\QueryTable;
use App\Contracts\FormContract;
use App\Enums\RawTypeEnum;
use App\Helpers\LocaleHelper;
use App\Models\Raw;
use App\Repositories\RawRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

final class ReadAndEditRaws
{
    public function __construct(
        private RawRepository $rawRepository,
    ) {}

    public function browse()
    {
        return view('web.admins.read', [
            'title' => 'Raws',
            'form' => $this->table(),
        ]);
    }

    public function read(string $id)
    {
        return view('web.admins.read', [
            'title' => 'Raws',
            'form' => $this->form($this->rawRepository->findMakeOrFail($id)),
        ]);
    }

    public function edit(string $id, Request $request)
    {
        return view('web.admins.read', [
            'title' => 'Raws',
            'form' => $this->form($this->rawRepository->findMakeOrFail($id))->submitRequest($request),
        ]);
    }

    private function table(): FormContract
    {
        $table = (new QueryTable(
            query: Raw::query(),
            id: 'raws',
            pageSize: 10,
            addURI: scoped_route('admins.edit', [
                'locale' => app()->getLocale(),
                'page' => 'raws',
                'id' => 'new',
            ]),
            columns: [
                new ModelColumn(
                    title: 'Actions',
                    actions: [
                        new ModelUrlAction(
                            title: '<i class="fa fa-pencil"></i>',
                            urlMaker: fn(Raw $model) => scoped_route('admins.read', [
                                'locale' => app()->getLocale(),
                                'page' => 'raws',
                                'id' => $model->getKey(),
                            ]),
                            style: ModelUrlAction::STYLE_WARNING,
                        ),
                        new ModelAction(title: '<i class="fa fa-trash"></i>', action: 'delete', style: ModelAction::STYLE_DANGER),
                    ],
                ),
                new ModelColumn(
                    attribute: 'id',
                    title: 'ID',
                    filter: new QueryFilter(id: 'id', rules: ['uuid'])
                ),
                new ModelColumn(
                    attribute: 'project_id',
                    title: 'Project',
                    filter: new QueryFilter(id: 'project_id', rules: ['uuid']),
                ),
                new ModelColumn(
                    attribute: 'user_id',
                    title: 'User',
                    filter: new QueryFilter(id: 'user_id', rules: ['uuid']),
                ),
                new ModelColumn(
                    attribute: 'type',
                    title: 'Type',
                    filter: new QueryFilter(id: 'type'),
                ),
                new ModelColumn(
                    attribute: 'uri',
                    title: 'URI',
                    filter: new QueryFilter(id: 'uri'),
                ),
                new ModelColumn(
                    attribute: 'created_at',
                    title: 'Created At',
                    filter: new QueryFilter(style: QueryFilter::STYLE_DATE, id: 'created_at'),
                ),
                new ModelColumn(
                    attribute: 'updated_at',
                    title: 'Updated At',
                    filter: new QueryFilter(style: QueryFilter::STYLE_DATE, id: 'updated_at'),
                ),
            ],
            batchActionCallbacks: [
                'delete' => static function (Collection $models) {
                    $models->each(fn(Model $model) => $model->delete());
                }
            ],
            modelActionCallbacks: [
                'delete' => static function (Model $model) {
                    $model->delete();
                }
            ],
        ));
        $table->addBatchAction(
            new BatchAction(
                title: '<i class="fa fa-trash"></i>&nbsp;Delete',
                action: new FormModal(
                    title: 'Raw deletion',
                    body: 'Do you really want to delete selected pages?<br>This action is irreversible.',
                    actions: [
                        new FormAction(title: 'Cancel', action: 'cancel'),
                        new FormAction(title: 'Delete', action: 'delete', forForm: $table),
                    ],
                ),
                style: BatchAction::STYLE_DANGER
            )
        );
        return $table;
    }

    public function form(Raw $raw): ModelForm
    {
        return new ModelForm(
            id: 'raws',
            model: $raw,
            fields: [
                new FormField(
                    attribute: 'id',
                    title: 'ID',
                    readOnly: true,
                ),
                new FormField(
                    attribute: 'type',
                    style: FormField::STYLE_SELECT,
                    title: 'Type',
                    options: RawTypeEnum::toLabels(),
                ),
                new FormField(
                    attribute: 'uri',
                    title: 'URI',
                ),
                new FormField(
                    attribute: 'locale',
                    style: FormField::STYLE_SELECT,
                    title: 'Locale',
                    options: LocaleHelper::getOptions()
                ),
                new FormField(
                    attribute: 'body',
                    style: FormField::STYLE_EDITOR,
                    title: 'Body',
                ),
                new FormField(
                    attribute: 'created_at',
                    title: 'Created at',
                    readOnly: true,
                ),
                new FormField(
                    attribute: 'updated_at',
                    title: 'Updated at',
                    readOnly: true,
                ),
            ],
        );
    }
}
