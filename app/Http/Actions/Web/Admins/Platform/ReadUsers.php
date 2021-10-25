<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins\Platform;

use App\Contracts\FormContract;
use App\Forms\ModelAction;
use App\Forms\ModelColumn;
use App\Forms\ModelUrlAction;
use App\Forms\QueryFilter;
use App\Forms\QueryTable;
use App\Models\Page;
use App\Models\User;

final class ReadUsers
{
    public function browse()
    {
        return view('web.admins.read', [
            'title' => 'Users',
            'form' => $this->table(),
        ]);
    }

    private function table(): FormContract
    {
        $table = new QueryTable(
            query: User::query(),
            id: 'users',
            pageSize: 10,
            columns: [
                new ModelColumn(
                    title: 'Actions',
                    actions: [
                        new ModelUrlAction(
                            title: '<i class="fa fa-eye"></i>',
                            urlMaker: fn(User $model) => scoped_route('users.read', [
                                'user' => $model->getKey(),
                                'locale' => app()->getLocale(),
                            ]),
                        ),
                    ],
                ),
                new ModelColumn(
                    attribute: 'id',
                    title: 'ID',
                    filter: new QueryFilter(id: 'id', rules: ['uuid'])
                ),
                new ModelColumn(
                    attribute: 'discord_id',
                    title: 'DiscordID',
                    filter: new QueryFilter(id: 'discord_id', rules: ['number'])
                ),
                new ModelColumn(
                    attribute: 'email',
                    title: 'Email',
                    filter: new QueryFilter(id: 'email', rules: ['email'])
                ),
                new ModelColumn(
                    attribute: 'name',
                    title: 'Name',
                    filter: new QueryFilter(id: 'name')
                ),
                new ModelColumn(
                    attribute: 'avatar',
                    title: 'Avatar',
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
        );
        return $table;
    }
}
