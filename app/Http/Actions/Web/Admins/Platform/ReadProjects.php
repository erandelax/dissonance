<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins\Platform;

use App\Contracts\FormContract;
use App\Forms\ModelColumn;
use App\Forms\QueryTable;
use App\Models\Project;

final class ReadProjects
{
    public function browse()
    {
        return view('web.admins.read', [
            'title' => 'Projects',
            'form' => app()->call([$this, 'table']),
        ]);
    }

    public function table(QueryTable $table): FormContract
    {
        $table
            ->setQuery(Project::query())
            ->addColumn(new ModelColumn(attribute: 'id', title: '#'))
            ->addColumn(new ModelColumn(attribute: 'host', title: 'Host'))
            ->addColumn(new ModelColumn(attribute: 'port', title: 'Port'))
            ->addColumn(new ModelColumn(attribute: 'title', title: 'Title'))
            ->addColumn(new ModelColumn(attribute: 'description', title: 'Description'))
            ->addColumn(new ModelColumn(attribute: 'avatar', title: 'Avatar'))
        ;
        return $table;
    }
}
