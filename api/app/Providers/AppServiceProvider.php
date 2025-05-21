<?php

namespace App\Providers;

use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\Interfaces\AuthRepositoryInterface;
use App\Services\Auth\AuthService;
use App\Services\Auth\Interfaces\AuthServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
