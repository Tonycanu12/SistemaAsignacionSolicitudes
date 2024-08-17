<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\UserService;
use App\Repositories\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Repositories\UserRepository::class, function($app){
            return new \App\Repositories\UserRepository();
        });

        // $this->app->bind(\App\Services\UserService::class, function ($app) {
        //     return new \App\Services\UserService($app->make(\App\Repositories\UserRepository::class));
        // });

        $this->app->bind(\App\Services\UserService::class, function($app){
            return new \App\Services\UserService($app->make(\App\Repositories\UserRepository::class));
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
