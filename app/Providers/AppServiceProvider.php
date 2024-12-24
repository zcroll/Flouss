<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Client\Request;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Pan\PanConfiguration;
use Illuminate\Support\Facades\Vite;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        JsonResource::withoutWrapping();
    
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::useWaterfallPrefetching(concurrency: 10);
    }
}
