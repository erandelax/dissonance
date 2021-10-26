<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Users;

use App\Entities\Locale;
use App\Entities\ProjectReference;
use App\Entities\UserReference;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Gate;

final class ReadUser
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function __invoke(ProjectReference $project, Locale $locale, UserReference $user)
    {
        $user = $this->userRepository->findByReference($user);
        return view(Gate::check('update-user', $user) ? 'web.users.edit' : 'web.users.read', [
            'user' => $user,
        ]);
    }
}
