<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Character;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class CharacterRepository
{
    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage): LengthAwarePaginator
    {
        return Character::paginate($perPage);
    }

    /**
     * @param User $user
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginateByUser(User $user, int $perPage): LengthAwarePaginator
    {
        return Character::whereUserId($user->getKey())->paginate($perPage);
    }
}
