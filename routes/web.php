<?php

use App\Http\Controllers\ActivityProgressController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\JobMatcherController;
use App\Http\Controllers\Result\ResultController;
use App\Http\Controllers\dashboard\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormationFilterController;
use App\Http\Controllers\DegreeFilterController;

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::controller(ActivityProgressController::class)->group(function () {
            Route::get('/activities', 'index')->name('activities');
            Route::post('/activity/submit', 'submit')->name('activity.submit');
        });

        Route::controller(CareerController::class)->group(function () {
            Route::get('/career/{id}', 'index')->name('career');
            Route::get('/career/{id}/workEnvironments', 'workEnvironments')->name('career.workEnvironments');
            Route::get('/career/{job}/personality', 'personality')->name('career.personality');
            Route::get('/career/{job}/how-to-become', 'howToBecome')->name('career.how-to-become');
        });

        Route::controller(ResultController::class)->group(function () {
            Route::get('/results', 'results')->name('results');
            Route::get('/results/{id}/personality', 'personality')->name('personality');
        });

        Route::get('/find-closest-job', [JobMatcherController::class, 'matchJobs']);
    });
});

Route::get('/formations', [FormationFilterController::class, 'index'])->name('formations.index');
Route::get('/formations/filter', [FormationFilterController::class, 'filter'])->name('formations.filter');
Route::get('/etablissements', [FormationFilterController::class, 'getEtablissements'])->name('etablissements.list');
Route::get('/degrees', [DegreeFilterController::class, 'index'])->name('degrees.index');
