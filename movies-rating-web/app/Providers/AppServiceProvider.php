<?php

namespace App\Providers;

use Illuminate\Container\Attributes\Singleton;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->Singleton(\App\Services\TmdbService::class, function ($app) {
            return new \App\Services\TmdbService();
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
