<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Users;

use App\Entities\Locale;
use App\Entities\ProjectReference;
use App\Entities\UserReference;
use App\Http\Requests\StoreProfileRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

final class EditUser
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function __invoke(ProjectReference $project, Locale $locale, UserReference $user, StoreProfileRequest $request)
    {
        $currentUser = Auth::user();
        $userModel = $this->userRepository->findByReference($user);
        if ($currentUser->can('update-user', $userModel)) {
            $data = $request->validated();
            if ($data['avatar'] ?? null) {
                /** @var UploadedFile $avatar */
                $avatar = $data['avatar'];
                $name = "{$userModel->discord_id}.{$avatar->getClientOriginalExtension()}";
                Storage::putFileAs('public/avatars', $avatar, $name);
                $userModel->avatar = Storage::url("avatars/{$name}");
            }
            if ($data['name'] ?? null) {
                $userModel->name = $data['name'];
            }
            $userModel->save();
        }
        return redirect(scoped_route('users.read', ['user' => $userModel->id, 'locale' => $locale]));
    }
}
