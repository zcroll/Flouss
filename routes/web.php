<?php

use App\Http\Controllers\Result\ResultController;
use App\Http\Controllers\Career\CareerController;
use App\Http\Controllers\Filters\DegreeFilterController;
use App\Http\Controllers\Filters\JobFilterController;
use App\Http\Controllers\Filters\FormationFilterController;
use App\Http\Controllers\Assessment\ActivityProgressController;
use App\Http\Controllers\Assessment\JobMatcherController;
use App\Http\Controllers\Assessment\JobFieldController;

use App\Http\Controllers\dashboard\DashboardController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::controller(ActivityProgressController::class)->group(function () {
            Route::get('/activities', 'index')->name('activity.index');
            Route::post('/activities', 'submitAnswer')->name('activity.submit-answer');
            Route::get('/activities/previous', 'previousActivity')->name('activity.previous');
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

        // Add the new route for JobFieldController
        Route::get('/job-fields', [JobFieldController::class, 'index'])->name('job.fields');
    });
});

Route::get('/formations', [FormationFilterController::class, 'index'])->name('formations.index');
Route::get('/formations/filter', [FormationFilterController::class, 'filter'])->name('formations.filter');
Route::get('/etablissements', [FormationFilterController::class, 'getEtablissements'])->name('etablissements.list');
Route::get('/degrees', [DegreeFilterController::class, 'index'])->name('degrees.index');
Route::get('/jobs', [JobFilterController::class, 'index'])->name('jobs.index');
