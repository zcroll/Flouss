<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Client\Request;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Pan\PanConfiguration;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        JsonResource::withoutWrapping();
        PanConfiguration::allowedAnalytics([
            'task-list-view',
            'task-filters-section',
            'task-actions-section',
            'task-create-button',
            'task-import-button',
            'task-status-filter',
            'task-priority-filter'
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
