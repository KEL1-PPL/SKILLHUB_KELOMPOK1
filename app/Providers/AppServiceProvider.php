<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Discount;
use App\Policies\DiscountPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the Discount policy
        Gate::policy(Discount::class, DiscountPolicy::class);
        
        // Allow all users to manage discounts
        Gate::define('manage-discounts', function ($user) {
            return true;
        });
    }
}
