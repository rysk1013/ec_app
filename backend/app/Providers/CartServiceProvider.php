<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Session\SessionManager;
use App\Contracts\CartContract;
use App\Services\DatabaseCartService;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CartContract::class, function ($app) {
            return new DatabaseCartService(
                $app->make(Guard::class),
                $app->make(SessionManager::class)
            );
        });

        $this->app->alias(CartContract::class, 'caart');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
