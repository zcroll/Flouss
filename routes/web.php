<?php

use App\Http\Controllers\ActivityProgressController;
use App\Http\Controllers\CareerController;
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
        Route::get('/results', 'results')->name('results');
    });

    Route::controller(CareerController::class)->group(function () {
        Route::get('/career/{id}', 'index')->name('career');
        Route::get('/career/{job}/abilities', 'abilities')->name('career.abilities');
        Route::get('/career/{job}/knowledge', 'knowledge')->name('career.knowledge');
        Route::get('/career/{job}/technologies', 'technologies')->name('career.technologies');
        Route::get('/career/{job}/tasks', 'tasks')->name('career.tasks');
        Route::get('/career/{job}/work-activities', 'workActivities')->name('career.work-activities');
    });
});
