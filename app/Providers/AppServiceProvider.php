<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TaxCalculationService; // Import service

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TaxCalculationService::class, function ($app) {
            return new TaxCalculationService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}