<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('update-page', function (User $user, Page $target) {
            return $user->getKey() !== null;
        });

        Gate::define('update-user', function (User $user, User $target) {
            return $user->getKey() === $target->getKey();
        });
    }
}
