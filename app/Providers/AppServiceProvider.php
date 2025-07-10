<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Check the current domain and conditionally redirect
        /*if ($_SERVER['HTTP_HOST'] != 'alembic-realestate.test' && !app()->runningInConsole()
        ) {
            header("Location: https://www.contracts.alembic.co.in/");
            exit;
        }*/
    }
}
