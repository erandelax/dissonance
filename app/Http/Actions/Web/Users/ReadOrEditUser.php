<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Users;

use App\Entities\Locale;
use App\Entities\ProjectReference;
use App\Entities\UserReference;
use App\Components\FormField;
use App\Components\ModelForm;
use App\Helpers\LocaleHelper;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
                new FormField(
                    attribute: 'displayAvatar',
                    style: FormField::STYLE_UPLOAD,
                    title: 'Avatar',
                    readOnly: $isReadOnly,
                ),
                new FormField(
                    attribute: 'name',
                    title: 'Name',
                    readOnly: $isReadOnly,
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
                    options: array_combine($timezones = \DateTimeZone::listIdentifiers(), $timezones),
                    readOnly: $isReadOnly,
                ),
                new FormField(
                    attribute: 'locale',
                    style: FormField::STYLE_SELECT,
                    title: 'Locale',
                    options: LocaleHelper::getOptions(),
                    readOnly: $isReadOnly,
                ),
                new FormField(
                    attribute: 'id',
                    title: 'ID',
                    readOnly: true,
                ),
                !$isReadOnly ? new FormField(
                    attribute: 'email',
                    title: 'Email',
                    readOnly: true,
                ) : null,
                new FormField(
                    attribute: 'discord_id',
                    title: 'Discord ID',
                    readOnly: true,
                ),
                new FormField(
                    attribute: 'nickname',
                    title: 'Discord Nickname',
                    readOnly: true,
                ),
                !$isReadOnly ? new FormField(
                    attribute: 'avatar',
                    title: 'Discord avatar',
                    readOnly: true,
                ) : null,
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
