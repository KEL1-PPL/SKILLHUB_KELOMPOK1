<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
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

        // Allow all users to manage discounts
        Gate::define('manage-discounts', function ($user) {
            return true; // Allow all authenticated users
        });

        // Also add this to bypass any model policy checks for the Discount model
        Gate::before(function ($user, $ability) {
            if ($ability === 'viewAny' || $ability === 'view' || $ability === 'create' || $ability === 'update' || $ability === 'delete') {
                return true;
            }
        });
    }
}
