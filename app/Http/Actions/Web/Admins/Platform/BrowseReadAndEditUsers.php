<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins\Platform;

use App\Contracts\FormContract;
use App\Components\Tables\ModelColumn;
use App\Components\Forms\FormField;
use App\Components\Forms\ModelForm;
use App\Components\Actions\ModelUrlAction;
use App\Components\Tables\QueryFilter;
use App\Components\Tables\QueryTable;
use App\Helpers\LocaleHelper;
use App\Models\User;
use Illuminate\Http\Request;

final class BrowseReadAndEditUsers
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
                        new ModelUrlAction(
                            title: '<i class="fa fa-pencil"></i>',
                            urlMaker: fn(User $model) => scoped_route('admins.read', [
                                'locale' => app()->getLocale(),
                                'page' => 'users',
                                'id' => $model->getKey(),
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
                    filter: new QueryFilter(id: 'discord_id', rules: ['numeric'])
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
            model: User::find($id),
            fields: [
                new FormField(
                    attribute: 'id',
                    title: 'ID',
                    readOnly: true,
                ),
                new FormField(
                    attribute: 'name',
                    title: 'Name'
                ),
                new FormField(
                    attribute: 'displayAvatar',
                    style: FormField::STYLE_UPLOAD,
                    title: 'Avatar'
                ),
                new FormField(
                    attribute: 'custom_avatar_id',
                    title: 'Avatar upload ID',
                    readOnly: true,
                ),
                new FormField(
                    attribute: 'timezone',
                    style: FormField::STYLE_SELECT,
                    title: 'Timezone',
                    options: array_combine($timezones = \DateTimeZone::listIdentifiers(), $timezones)
                ),
                new FormField(
                    attribute: 'locale',
                    style: FormField::STYLE_SELECT,
                    title: 'Locale',
                    options: LocaleHelper::getOptions()
                ),
                new FormField(
                    attribute: 'email',
                    title: 'Email',
                    description: 'Not editable (is imported from Discord on every auth)',
                    readOnly: true,
                ),
                new FormField(
                    attribute: 'discord_id',
                    title: 'Discord ID',
                    description: 'Not editable (is imported from Discord on every auth)',
                    readOnly: true,
                ),
                new FormField(
                    attribute: 'nickname',
                    title: 'Discord Nickname',
                    description: 'Not editable (is imported from Discord on every auth)',
                    readOnly: true,
                ),
                new FormField(
                    attribute: 'avatar',
                    title: 'Discord avatar',
                    description: 'Not editable (is imported from Discord on every auth)',
                    readOnly: true,
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
        return $form;
    }
}
