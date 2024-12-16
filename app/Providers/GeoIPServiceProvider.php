<?php

namespace App\Providers;

use App\Services\GeoIPService;
use App\Services\PageVisitTracker;
use Illuminate\Support\ServiceProvider;

class GeoIPServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GeoIPService::class);
        $this->app->singleton(PageVisitTracker::class);
    }
} 