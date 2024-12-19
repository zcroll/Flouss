<?php

namespace App\Providers;

use App\Repositories\Contracts\IPLocationRepository;
use App\Repositories\EloquentIPLocationRepository;
use App\Services\Contracts\GeoLocationService;
use App\Services\IPGeolocationService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class GeoIPServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repository
        $this->app->bind(IPLocationRepository::class, EloquentIPLocationRepository::class);

        // Bind service with dependencies
        $this->app->bind(GeoLocationService::class, function ($app) {
            return new IPGeolocationService(
                repository: $app->make(IPLocationRepository::class),
                apiKey: config('services.ipgeolocation.key'),
                client: $app->make(Http::class)
            );
        });
    }
} 