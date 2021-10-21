<?php

declare(strict_types=1);

namespace App\Http\Actions\Pages;

use App\Entities\DiscordID;
use App\Http\Actions\Action;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\RouteAttributes\Attributes\Post;

final class UpdateProfile extends Action
{
    #[Post(uri: '/{locale}/u/{discordID}', name: 'profile', middleware: 'web')]
    public function __invoke(DiscordID $discordID, UpdateProfileRequest $updateRequest): RedirectResponse
    {
        $user = Auth::user();
        $profileOwner = User::whereDiscordId($discordID)->firstOrFail();
        if ($user->id === $profileOwner->id) {
            $data = $updateRequest->validated();
            if ($data['avatar'] ?? null) {
                /** @var UploadedFile $avatar */
                $avatar = $data['avatar'];
                $name = "{$profileOwner->discord_id}.{$avatar->getClientOriginalExtension()}";
                Storage::putFileAs('public/avatars', $avatar, $name);
                $profileOwner->avatar = Storage::url("avatars/{$name}");
            }
            if ($data['name'] ?? null) {
                $profileOwner->name = $data['name'];
            }
            $profileOwner->save();
        }
        return redirect(route('profile', ['discordID' => $profileOwner->discord_id]));
    }
}
