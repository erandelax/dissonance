<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins\Platform;

use App\Contracts\FormContract;

final class ReadSettings
{
    public function read()
    {
        return view('web.admins.read', ['form' => null, 'title' => 'Settings']);
    }

    public function form(): FormContract
    {

    }
}
