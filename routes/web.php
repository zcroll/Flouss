<?php

use App\Http\Controllers\ActivityProgressController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\JobMatcherController;
use App\Http\Controllers\Result\ResultController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;




        Route::get('/', function () {
            return Inertia::render('Welcome', [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
            ]);
        });

        Route::middleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
        ])->group(function () {
            Route::get('/dashboard', function () {
                return Inertia::render('Dashboard');
            })->name('dashboard');

            Route::controller(ActivityProgressController::class)->group(function () {
                Route::get('/activities', 'index')->name('activities');
                Route::post('/activity/submit', 'submit')->name('activity.submit');
            });

            Route::controller(CareerController::class)->group(function () {
                Route::get('/career/{id}', 'index')->name('career');
                Route::get('/career/{id}/workEnvironments', 'workEnvironments')->name('career.workEnvironments');
                Route::get('/career/{job}/personality', 'personality')->name('career.personality');
            });
        });
    Route::controller(ResultController::class)->group(function () {
        Route::get('/results', 'results')->name('results');
        Route::get('/results/{id}/personality', 'personality')->name('personality');
    });
Route::get('/find-closest-job', [JobMatcherController::class, 'matchJobs']);
