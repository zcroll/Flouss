<?php

namespace App\Services;

use App\Http\Controllers\Test\{
    HollandCodeController,
    BasicInterestController,
    DegreeTestStageController,
    PersonalityController,
    ResultTest
};
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class TestStageService
{
    private const STAGES = [
        'holland_codes',
        'basic_interests',
        'degree',
        'personality'
    ];

    private const SESSION_KEYS = [
        'holland_codes' => 'holland_codes_progress',
        'basic_interests' => 'basic_interest_progress',
        'degree' => 'degree_progress',
        'personality' => 'personality_progress'
    ];

    private const CONTROLLERS = [
        'holland_codes' => HollandCodeController::class,
        'basic_interests' => BasicInterestController::class,
        'degree' => DegreeTestStageController::class,
        'personality' => PersonalityController::class
    ];

    public function getSessionKey(string $stage): ?string
    {
        return self::SESSION_KEYS[$stage] ?? null;
    }

    public function getStageController(string $stage)
    {
        $controllerClass = self::CONTROLLERS[$stage] ?? null;
        return $controllerClass ? app($controllerClass) : null;
    }

    public function getNextStage(string $currentStage): ?string
    {
        $currentIndex = array_search($currentStage, self::STAGES);
        return ($currentIndex !== false && $currentIndex < count(self::STAGES) - 1) 
            ? self::STAGES[$currentIndex + 1] 
            : null;
    }

    public function getPreviousStage(string $currentStage): ?string
    {
        $currentIndex = array_search($currentStage, self::STAGES);
        return ($currentIndex !== false && $currentIndex > 0) 
            ? self::STAGES[$currentIndex - 1] 
            : null;
    }

    public function determineCurrentStage(): string
    {
        $currentStage = Session::get('current_test_stage');

        if (!$currentStage) {
            $hollandProgress = Session::get('holland_codes_progress');
            
            if (!$hollandProgress || !($hollandProgress['completed'] ?? false)) {
                $currentStage = 'holland_codes';
            } else {
                $basicProgress = Session::get('basic_interest_progress');
                $currentStage = (!$basicProgress || !($basicProgress['completed'] ?? false))
                    ? 'basic_interests'
                    : 'holland_codes';
            }

            Session::put('current_test_stage', $currentStage);
        }

        return $currentStage;
    }

    public function getAllProgress(): array
    {
        $defaultProgress = [
            'current_index' => 0,
            'responses' => [],
            'completed' => false,
            'progress_percentage' => 0
        ];

        return array_map(function($key) use ($defaultProgress) {
            return Session::get($key, $defaultProgress);
        }, self::SESSION_KEYS);
    }

    public function checkAllStagesCompleted(): ?array
    {
        $allProgress = $this->getAllProgress();
        
        $allCompleted = collect($allProgress)->every(function($progress) {
            return $progress['completed'] ?? false;
        });

        if ($allCompleted && Session::has('result_user')) {
            return (new ResultTest())->checkAllTestsCompleted();
        }

        return null;
    }

    public function getStageData(string $stage): array
    {
        $progress = Session::get($this->getSessionKey($stage), [
            'current_index' => 0,
            'responses' => [],
            'completed' => false,
            'progress_percentage' => 0
        ]);

        Log::info("TestStageController: {$stage} progress", [
            'progress' => $progress,
            'session_id' => Session::getId()
        ]);

        return ['progress' => $progress];
    }
} 