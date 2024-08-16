<?php

use App\Http\Controllers\ActivityProgressController;
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
});
Route::get('/activities', [ActivityProgressController::class, 'index']);

//Route::post('/activity', [ActivityProgressController::class, 'submit']);
Route::post('/activity/submit', [ActivityProgressController::class, 'submit']);



Route::get('/results', [ActivityProgressController::class, 'results'])->name('results');


