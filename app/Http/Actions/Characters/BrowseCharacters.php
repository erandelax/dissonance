<?php

declare(strict_types=1);

namespace App\Http\Actions\Characters;

use App\Entities\DiscordID;
use App\Entities\Locale;
use App\Repositories\CharacterRepository;
use App\Repositories\UserRepository;
use Spatie\RouteAttributes\Attributes\Get;

final class BrowseCharacters
{
    /**
     * BrowseCharacters constructor.
     * @param CharacterRepository $characterRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        private CharacterRepository $characterRepository,
        private UserRepository $userRepository,
    )
    {
    }

    #[Get(uri: '/{locale}/c/{discordID?}', name: 'characters.list', middleware: 'web')]
    public function __invoke(Locale $locale, DiscordID|null $discordID): \Illuminate\Contracts\View\View
    {
        $user = null;
        if (null !== $discordID) {
            $user = $this->userRepository->findByDiscordID($discordID);
        }
        return view('characters.character_list', [
            'characters' => $user === null
                ? $this->characterRepository->paginate(10)
                : $this->characterRepository->paginateByUser($user, 10),
        ]);
    }
}
