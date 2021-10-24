<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Auths;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class Logout
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
       Auth::logout();
       return redirect()->back();
    }
}
