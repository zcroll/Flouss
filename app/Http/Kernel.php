<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // ... other middleware
            \App\Http\Middleware\AuthenticationMonitoring::class,
        ],
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        //
    }

    /**
     * Configure the error handlers for the application.
     *
     * @return void
     */
    protected function configureExceptionHandling()
    {
        //
    }

    /**
     * Configure the request middleware for the application.
     *
     * @return void
     */
    protected function configureRequestMiddleware()
    {
        //
    }

    /**
     * Configure the response middleware for the application.
     *
     * @return void
     */
    protected function configureResponseMiddleware()
    {
        //
    }

    /**
     * Configure the route middleware for the application.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the middleware aliases for the application.
     *
     * Aliases may be used instead of class names to assign middleware to routes and groups.
     *
     * @var array
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class.':60,1',
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];
} 