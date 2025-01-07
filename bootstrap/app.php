<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelFlare\Facades\Flare;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        // Register core service providers
        \Illuminate\Filesystem\FilesystemServiceProvider::class,
        \App\Providers\AppServiceProvider::class,
        \App\Providers\AnalyticsServiceProvider::class,
        \Recca0120\LaravelErd\LaravelErdServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\SetLanguage::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            // \App\Http\Middleware\TrackPageVisits::class,
        ]);

        $middleware->alias([
            'check.result' => \App\Http\Middleware\CheckUserResult::class,
        ]);

        $middleware->validateCsrfTokens(
            except: ['stripe/*', 'webhooks/*']
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontReport([
            // Add any exceptions you don't want to report
        ]);

        Flare::handles($exceptions);
    })
    ->create();
