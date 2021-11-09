<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Users;

use App\Entities\Locale;
use App\Entities\ProjectReference;
use App\Entities\UserReference;
use App\Components\Forms\ModelForm;
use App\Helpers\LocaleHelper;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Components\Forms\Fields;

final class ReadOrEditUser
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function __invoke(ProjectReference $project, Locale $locale, UserReference $user, Request $request)
    {
        $user = $this->userRepository->findByReference($user);
        $form = $this->form($user);
        if ($request->method() === 'POST') {
            $form->submitRequest($request);
        }
        return view('web.users.read-and-edit', [
            'form' => $form,
            'user' => $user,
        ]);
    }

    public function form(User $user): ModelForm
    {
        $isReadOnly = !Gate::check('update-user', $user);
        return new ModelForm(
            id: 'profile',
            model: $user,
            canSubmit: !$isReadOnly,
            fields: [
                new Fields\UploadField(
                    attribute: 'displayAvatar',
                    title: 'Avatar',
                    readOnly: $isReadOnly,
                ),
                new Fields\TextField(
                    attribute: 'name',
                    title: 'Name',
                    readOnly: $isReadOnly,
                ),
                new Fields\TextField(
                    attribute: 'custom_avatar_id',
                    title: 'Avatar upload ID',
                    readOnly: true,
                ),
                new Fields\SelectField(
                    attribute: 'timezone',
                    title: 'Timezone',
                    options: array_combine($timezones = \DateTimeZone::listIdentifiers(), $timezones),
                    readOnly: $isReadOnly,
                ),
                new Fields\SelectField(
                    attribute: 'locale',
                    title: 'Locale',
                    options: LocaleHelper::getOptions(),
                    readOnly: $isReadOnly,
                ),
                new Fields\TextField(
                    attribute: 'id',
                    title: 'ID',
                    readOnly: true,
                ),
                !$isReadOnly ? new Fields\TextField(
                    attribute: 'email',
                    title: 'Email',
                    readOnly: true,
                ) : null,
                new Fields\TextField(
                    attribute: 'discord_id',
                    title: 'Discord ID',
                    readOnly: true,
                ),
                new Fields\TextField(
                    attribute: 'nickname',
                    title: 'Discord Nickname',
                    readOnly: true,
                ),
                !$isReadOnly ? new Fields\TextField(
                    attribute: 'avatar',
                    title: 'Discord avatar',
                    readOnly: true,
                ) : null,
                new Fields\TextField(
                    attribute: 'created_at',
                    title: 'Created at',
                    readOnly: true,
                ),
                new Fields\TextField(
                    attribute: 'updated_at',
                    title: 'Updated at',
                    readOnly: true,
                ),
            ],
        );
    }
}
