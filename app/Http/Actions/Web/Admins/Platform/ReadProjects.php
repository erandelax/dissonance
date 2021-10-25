<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins\Platform;

use App\Contracts\FormContract;
use App\Forms\ModelColumn;
use App\Forms\ModelUrlAction;
use App\Forms\QueryTable;
use App\Models\Project;

final class ReadProjects
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
}
