<?php

namespace App\Providers;

use App\Services\AnalyticsService;
use App\Services\PageVisitTracker;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class AnalyticsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PageVisitTracker::class);
        
        $this->app->singleton(AnalyticsService::class, function (Application $app) {
            return new AnalyticsService();
        });
    }

    public function boot(): void
    {
        if ($this->app->environment('local')) {
            $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        }
    }
} 