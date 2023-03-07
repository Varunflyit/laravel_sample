<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use Ecommify\Core\Models\Company;
use Ecommify\Core\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
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

        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });
        Gate::define('admin', function (User $user) {
            return $user->isSuperAdmin() || $user->isAdmin()
                ? Response::allow()
                : Response::deny(trans('app.you_dont_have_access'));
        });
        Gate::define('view-company-data', function (User $user, Company $company) {
            return $user->isCompanyMember($company) || $user->isAdmin()
                ? Response::allow()
                : Response::deny(trans('app.you_dont_have_access_to_this_company'));
        });
    }
}
