<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins\Project;

final class ReadUsers
{
    public function browse()
    {
        return view('web.admins.read', ['form' => null]);
    }
}
