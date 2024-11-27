<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Result\ResultController;
use App\Http\Controllers\Career\CareerController;
use App\Http\Controllers\Filters\DegreeFilterController;
use App\Http\Controllers\Filters\JobFilterController;
use App\Http\Controllers\Filters\FormationFilterController;
use App\Http\Controllers\Assessment\ActivityProgressController;
use App\Http\Controllers\Assessment\JobMatcherController;
use App\Http\Controllers\Assessment\JobFieldController;
use App\Http\Controllers\Degree\DegreeController;
use App\Http\Controllers\dashboard\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\test\MainTestController;
use App\Http\Controllers\test\TestController;
use App\Http\Controllers\test\WelcomeBackController;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Test\HollandCodeController;
use App\Http\Controllers\Test\BasicInterestController;
use App\Http\Controllers\Test\TestStageController;

// Google Login Routes (place these BEFORE any auth middleware groups)
Route::get('auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])
    ->name('auth.google')
    ->middleware('guest');

Route::get('auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback'])
    ->name('auth.google.callback')
    ->middleware('guest');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
        Route::delete('/favorites/{favorite}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
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

        Route::get('/main-test', [MainTestController::class, 'index'])->name('main-test');
        Route::post('/main-test/store-holland-code', [MainTestController::class, 'storeHollandCodeResponse'])->name('store-holland-code-response');
        Route::post('/main-test/store-basic-interest', [MainTestController::class, 'storeBasicInterestResponse'])->name('store-basic-interest-response');
        Route::post('/test/go-back', [MainTestController::class, 'goBack'])->name('test.go-back');

        Route::get('/degree/{degreeSlug}', [DegreeController::class, 'index'])->name('degree.show');

        Route::prefix('degree')->group(function () {
            Route::get('{slug}/skills', [DegreeController::class, 'skills'])->name('degree.skills');
            Route::get('{slug}/jobs', [DegreeController::class, 'jobs'])->name('degree.jobs');
            Route::get('{slug}/how-to-obtain', [DegreeController::class, 'howToObtain'])->name('degree.howToObtain');
        });

        Route::get('/formations', [FormationFilterController::class, 'index'])->name('formations.index');
        Route::get('/formations/filter', [FormationFilterController::class, 'filter'])->name('formations.filter');
        Route::get('/etablissements', [FormationFilterController::class, 'getEtablissements'])->name('etablissements.list');
        Route::get('/degrees', [DegreeFilterController::class, 'index'])->name('degrees.index');
        Route::get('/jobs', [JobFilterController::class, 'index'])->name('jobs.index');

        // Chat routes
        Route::post('/chat', [ApiController::class, 'sendMessage'])->name('chat.send');

        // Welcome back routes
        Route::get('/welcome-back/show', [WelcomeBackController::class, 'showWelcomeBack'])->name('welcome-back.show');
        Route::post('/welcome-back/set-shown', [WelcomeBackController::class, 'setWelcomeBackShown'])->name('welcome-back.set-shown');

        Route::middleware(['auth'])->group(function () {
            // Holland Codes SPA routes
            Route::prefix('holland-codes')->group(function () {
                // Main SPA route
                Route::get('/', [HollandCodeController::class, 'index'])
                    ->name('holland-codes.index');
                
                // Store response endpoint
                Route::post('/store-response', [HollandCodeController::class, 'storeResponse'])
                    ->name('holland-codes.store-response');
                
                // Go back endpoint
                Route::post('/go-back', [HollandCodeController::class, 'goBack'])
                    ->name('holland-codes.go-back');
            });

            // Basic Interest routes
            Route::prefix('basic-interest')->group(function () {
                Route::get('/', [BasicInterestController::class, 'index'])
                    ->name('basic-interest.index');
                
                Route::post('/store-response', [BasicInterestController::class, 'storeResponse'])
                    ->name('basic-interest.store-response');
                
                Route::post('/go-back', [BasicInterestController::class, 'goBack'])
                    ->name('basic-interest.go-back');
            });

            // Add stage transition route
            Route::post('/test/change-stage', [TestStageController::class, 'changeStage'])
                ->name('test.change-stage');
        });
    });
});

Route::get('/how-it-works', function () {
    return Inertia::render('HomePage/HowItWork');
})->name('how-it-works');

Route::get('/interests', function () {
    return Inertia::render('HomePage/Interests');
})->name('interests');

Route::get('/test-preview', function () {
    return Inertia::render('Test/TestPreview2');
})->name('test-preview');

Route::middleware(['auth'])->group(function () {
    Route::get('/test', [TestController::class, 'index'])->name('test.index');
    Route::post('/test/progress', [TestController::class, 'updateProgress'])->name('test.progress');
});

Route::post('language/', LanguageController::class)->name('language.switch');

Route::get('/test-data', [JobMatcherController::class, 'matchJobs'])->name('test-data');

Route::get('/test', [TestController::class, 'index'])->name('test.index');

Route::get('/personality-archetype', [TestController::class, 'personalityArchetype'])->name('personality-archetype');
Route::get('/career-matches', [TestController::class, 'careerMatches']);
Route::get('/degree-matches', [TestController::class, 'degreeMatches']);
Route::get('/final-results', [TestController::class, 'finalResults']);
Route::post('/store-answer', [TestController::class, 'storeAnswer'])->name('store-answer');

Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

Route::get('/testt', function () {
    return Inertia::render('Result/Test');
})->name('testt');

Route::get('/im', function () {
  return Inertia::render('im');
})->name('im');

Route::get('/career-test', function () {
  return Inertia::render('Career-Test');
})->name('Career-Test');

Route::get('/for-organizations', function () {
  return Inertia::render('For-organizations');
})->name('For-organizations');

Route::get('/ruller', function () {
  return Inertia::render('ruller');
});

Route::get('/api',[ApiController::class,'index']);


