<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins\Project;

final class ReadSettings
{
    public function read()
    {
        return view('web.admins.read', ['form' => null]);
    }
}