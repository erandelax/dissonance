<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins\Platform;

use App\Contracts\FormContract;
use App\Forms\ModelColumn;
use App\Forms\ModelField;
use App\Forms\ModelForm;
use App\Forms\ModelUrlAction;
use App\Forms\QueryTable;
use App\Helpers\LocaleHelper;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

final class BrowseReadAndEditProjects
{
    public function browse()
    {
        return view('web.admins.read', [
            'title' => 'Projects',
            'form' => $this->table(),
        ]);
    }

    public function table(): FormContract
    {
        $table = new QueryTable(
            query: Project::query(),
            columns: [
                new ModelColumn(
                    title: 'Actions',
                    actions: [
                        new ModelUrlAction(
                            title: '<i class="fa fa-eye"></i>',
                            urlMaker: fn(Project $model) => route('subdirectory:projects.read', [
                                'project' => $model->address,
                                'locale' => app()->getLocale(),
                            ]),
                        ),
                        new ModelUrlAction(
                            title: '<i class="fa fa-pencil"></i>',
                            urlMaker: fn(Project $model) => scoped_route('admins.read', [
                                'locale' => app()->getLocale(),
                                'page' => 'projects',
                                'id' => $model->getKey(),
                            ]),
                        ),
                    ],
                ),
                new ModelColumn(attribute: 'id', title: 'ID'),
                new ModelColumn(attribute: 'host', title: 'Host'),
                new ModelColumn(attribute: 'port', title: 'Port'),
                new ModelColumn(attribute: 'title', title: 'Title'),
                new ModelColumn(attribute: 'description', title: 'Description'),
                new ModelColumn(attribute: 'avatar', title: 'Avatar'),
            ]
        );
        return $table;
    }

    public function read(string $id)
    {
        return view('web.admins.read', [
            'title' => 'Users',
            'form' => $this->form($id),
        ]);
    }

    public function edit(string $id, Request $request)
    {
        return view('web.admins.read', [
            'title' => 'Users',
            'form' => $this->form($id)->submitRequest($request),
        ]);
    }

    public function form(string $id): ModelForm
    {
        $form = new ModelForm(
            id: $id,
            model: Project::find($id),
            fields: [
                new ModelField(
                    attribute: 'id',
                    title: 'ID',
                    readOnly: true,
                ),
                new ModelField(
                    attribute: 'title',
                    title: 'Title'
                ),
                new ModelField(
                    attribute: 'description',
                    style: ModelField::STYLE_TEXTAREA,
                    title: 'Description',
                ),
                new ModelField(
                    attribute: 'created_at',
                    title: 'Created at',
                    readOnly: true,
                ),
                new ModelField(
                    attribute: 'updated_at',
                    title: 'Updated at',
                    readOnly: true,
                ),
            ],
        );
        return $form;
    }
}
