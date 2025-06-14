<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\test\TestController;
use App\Http\Controllers\ApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/personality-archetype', [TestController::class, 'personalityArchetype']);
    Route::get('/career-matches', [TestController::class, 'careerMatches']);
    Route::get('/degree-matches', [TestController::class, 'degreeMatches']);
    Route::get('/final-results', [TestController::class, 'finalResults']);
    
    // Chat routes
    Route::post('/chat', [ApiController::class, 'sendMessage']);
    Route::get('/chat/questions', [ApiController::class, 'getQuestions']);
});
