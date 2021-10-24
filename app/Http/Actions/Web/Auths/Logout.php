<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Auths;

use App\Entities\ProjectReference;
use Illuminate\Support\Facades\Auth;

final class Logout
{
    /**
     * @param \App\Entities\ProjectReference $project
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(ProjectReference $project)
    {
       Auth::logout();
       return redirect()->back();
    }
}
