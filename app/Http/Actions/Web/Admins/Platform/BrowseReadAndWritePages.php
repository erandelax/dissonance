<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins\Platform;

use App\Contracts\FormContract;
use App\Forms\ModelAction;
use App\Forms\FormField;
use App\Forms\FormFieldMarkdownPreview;
use App\Forms\ModelForm;
use App\Forms\ModelUrlAction;
use App\Forms\QueryFilter;
use App\Forms\FormAction;
use App\Forms\FormModal;
use App\Forms\BatchAction;
use App\Forms\ModelColumn;
use App\Forms\QueryTable;
use App\Helpers\LocaleHelper;
use App\Models\Config;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

final class BrowseReadAndWritePages
{
    public function browse()
    {
        return view('web.admins.read', [
            'title' => 'Pages',
            'form' => $this->table(),
        ]);
    }

    private function table(): FormContract
    {
        $table = (new QueryTable(
            query: Page::query()->withCount('revisions'),
            id: 'pages',
            pageSize: 10,
            columns: [
                new ModelColumn(
                    title: 'Actions',
                    actions: [
                        new ModelUrlAction(
                            title: '<i class="fa fa-pencil"></i>',
                            urlMaker: fn(Page $model) => scoped_route('admins.read', [
                                'locale' => app()->getLocale(),
                                'page' => 'pages',
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
                    attribute: 'locale',
                    title: 'Locale',
                    filter: new QueryFilter(
                        style: QueryFilter::STYLE_OPTIONS,
                        id: 'locale',
                        options: array_merge([null => null], LocaleHelper::getOptions())
                    ),
                ),
                new ModelColumn(
                    attribute: 'slug',
                    title: 'Slug',
                    filter: new QueryFilter(id: 'slug'),
                ),
                new ModelColumn(
                    attribute: 'title',
                    title: 'Title',
                    filter: new QueryFilter(type: QueryFilter::TYPE_LIKE, id: 'title'),
                ),
                new ModelColumn(
                    attribute: 'revisions_count',
                    title: 'Revisions'
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
                    title: 'Page deletion',
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


    public function read(string $id)
    {
        return view('web.admins.read', [
            'title' => 'Pages',
            'form' => $this->form(Page::find($id)),
        ]);
    }

    public function edit(string $id, Request $request)
    {
        return view('web.admins.read', [
            'title' => 'Pages',
            'form' => $this->form(Page::find($id))->submitRequest($request),
        ]);
    }

    public function form(Page $page): ModelForm
    {
        return new ModelForm(
            id: 'pages',
            model: $page,
            fields: [
                new FormField(
                    attribute: 'id',
                    title: 'ID',
                    readOnly: true,
                ),
                new FormField(
                    attribute: 'slug',
                    title: 'Slug',
                ),
                new FormField(
                    attribute: 'locale',
                    style: FormField::STYLE_SELECT,
                    title: 'Locale',
                    options: LocaleHelper::getOptions()
                ),
                new FormField(
                    attribute: 'title',
                    title: 'Title',
                ),
                $source = new FormField(
                    attribute: 'content',
                    style: FormField::STYLE_MARKDOWN,
                    title: 'Content',
                ),
                new FormFieldMarkdownPreview(
                    source: $source,
                    title: 'Preview',
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
