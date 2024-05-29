<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;

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
        // Set default string length as Spatie docs suggest
        // https://spatie.be/docs/laravel-permission/v6/prerequisites
        Schema::defaultStringLength(125);

        // Implicitly grant "admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        /**
         * Gate::before(function ($user, $ability) {
         *    return $user->hasRole('admin') ? true : null;
         * });
         */
    }
}
