<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class TestStageController extends Controller
{
    private $stages = [
        'holland_codes',
        'basic_interests',
        'degree',
        'personality'
    ];

    public function changeStage(Request $request)
    {
        \Log::info('TestStageController: Starting stage change', $request->all());

        try {
            $validated = $request->validate([
                'fromStage' => 'required|string',
                'toStage' => 'required|string'
            ]);

            // Get progress for current stage
            $sessionKey = $this->getSessionKey($validated['fromStage']);
            $fromStageProgress = Session::get($sessionKey, [
                'completed' => false
            ]);

            if (!$fromStageProgress['completed']) {
                return response()->json([
                    'error' => 'Previous stage not completed',
                    'currentStage' => $validated['fromStage'],
                    'progress' => $fromStageProgress
                ], 400);
            }

            // Store the current stage in session
            Session::put('current_test_stage', $validated['toStage']);

            // Get stage-specific data for the new stage
            $stageData = $this->getStageData($validated['toStage']);

            return response()->json([
                'success' => true,
                'currentStage' => $validated['toStage'],
                'stageData' => $stageData
            ]);

        } catch (\Exception $e) {
            \Log::error('TestStageController: Stage transition failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to change stage',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeResponse(Request $request)
    {
        try {
            $validated = $request->validate([
                'itemId' => 'required|integer',
                'answer' => 'required|integer|between:0,5',
                'type' => 'required|string|in:answered,skipped',
                'category' => 'required|string',
                'testStage' => 'required|string'
            ]);

            // Get the appropriate controller based on test stage
            $controller = $this->getStageController($validated['testStage']);
            if (!$controller) {
                throw new \Exception('Invalid test stage');
            }

            // Store the response using the stage-specific controller
            $response = $controller->storeResponse($request);

            // Check if all stages are completed after storing the response
            $allProgress = [
                'holland_codes' => Session::get('holland_codes_progress', [
                    'completed' => false
                ]),
                'basic_interests' => Session::get('basic_interest_progress', [
                    'completed' => false
                ]),
                'degree' => Session::get('degree_progress', [
                    'completed' => false
                ]),
                'personality' => Session::get('personality_progress', [
                    'completed' => false
                ])
            ];

            $allCompleted = !in_array(false, array_column($allProgress, 'completed'));

            if ($allCompleted && Session::has('result_user')) {
                $resultTest = new ResultTest();
                $saveResult = $resultTest->checkAllTestsCompleted();

                return response()->json([
                    'response' => $response,
                    'allCompleted' => true,
                    'saveResult' => $saveResult
                ]);
            }

            return $response;

        } catch (\Exception $e) {
            \Log::error('TestStageController: Error storing response', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return Inertia::render('Test/MainTest', [
                'error' => 'Failed to store response: ' . $e->getMessage(),
                'testStage' => $request->input('testStage', 'holland_codes')
            ]);
        }
    }

    public function getCurrentStage()
    {
        $currentStage = Session::get('current_test_stage');

        // If no stage is set in session, determine the appropriate stage
        if (!$currentStage) {
            // Check holland_codes_progress first
            $hollandProgress = Session::get('holland_codes_progress');
            if (!$hollandProgress || !($hollandProgress['completed'] ?? false)) {
                $currentStage = 'holland_codes';
            } else {
                // Check basic_interests_progress
                $basicProgress = Session::get('basic_interest_progress');
                if (!$basicProgress || !($basicProgress['completed'] ?? false)) {
                    $currentStage = 'basic_interests';
                } else {
                    $currentStage = 'holland_codes'; // Default if all complete
                }
            }

            // Store the determined stage
            Session::put('current_test_stage', $currentStage);
        }

        // Get all stage progress data
        $allProgress = [
            'holland_codes' => Session::get('holland_codes_progress', [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0
            ]),
            'basic_interests' => Session::get('basic_interest_progress', [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0
            ]),
            'degree' => Session::get('degree_progress', [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0
            ]),
            'personality' => Session::get('personality_progress', [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0
            ])
        ];

        $resultUser = Session::get('result_user');
        ds(['result_user' => $resultUser]);

        // Check if all stages are completed
        $completionResult = $this->checkAllStagesCompleted();

        \Log::info('TestStageController: Getting current stage', [
            'stage' => $currentStage,
            'progress' => $allProgress,
            'session_id' => Session::getId()
        ]);

        return response()->json([
            'currentStage' => $currentStage,
            'progress' => $allProgress,
            'completionResult' => $completionResult
        ]);
    }

    public function getNextStage($currentStage)
    {
        $currentIndex = array_search($currentStage, $this->stages);
        if ($currentIndex !== false && $currentIndex < count($this->stages) - 1) {
            return $this->stages[$currentIndex + 1];
        }
        return null;
    }

    public function getPreviousStage($currentStage)
    {
        $currentIndex = array_search($currentStage, $this->stages);
        if ($currentIndex !== false && $currentIndex > 0) {
            return $this->stages[$currentIndex - 1];
        }
        return null;
    }

    private function getSessionKey($stage)
    {
        $keyMap = [
            'holland_codes' => 'holland_codes_progress',
            'basic_interests' => 'basic_interest_progress',
            'degree' => 'degree_progress',
            'personality' => 'personality_progress'
        ];

        return $keyMap[$stage] ?? null;
    }

    private function getStageController($stage)
    {
        $controllerMap = [
            'holland_codes' => app(HollandCodeController::class),
            'basic_interests' => app(BasicInterestController::class),
            'degree' => app(DegreeTestStageController::class),
            'personality' => app(PersonalityController::class)
        ];

        return $controllerMap[$stage] ?? null;
    }

    private function getStageData($stage)
    {
        $stageData = [];

        switch ($stage) {
            case 'basic_interests':
                $progress = Session::get('basic_interest_progress', [
                    'current_index' => 0,
                    'responses' => [],
                    'completed' => false,
                    'progress_percentage' => 0
                ]);

                \Log::info('TestStageController: Basic Interest progress', [
                    'progress' => $progress,
                    'session_id' => Session::getId()
                ]);

                $stageData = [
                    'progress' => $progress
                ];
                break;

            case 'holland_codes':
                $progress = Session::get('holland_codes_progress', [
                    'current_index' => 0,
                    'responses' => [],
                    'completed' => false,
                    'progress_percentage' => 0
                ]);

                \Log::info('TestStageController: Holland Codes progress', [
                    'progress' => $progress,
                    'session_id' => Session::getId()
                ]);

                $stageData = [
                    'progress' => $progress
                ];
                break;

            case 'degree':
                $progress = Session::get('degree_progress', [
                    'current_index' => 0,
                    'responses' => [],
                    'completed' => false,
                    'progress_percentage' => 0,
                    'degreeMatching' => null
                ]);

                \Log::info('TestStageController: Degree progress', [
                    'progress' => $progress,
                    'session_id' => Session::getId()
                ]);

                $stageData = [
                    'progress' => $progress
                ];
                break;

            case 'personality':
                $progress = Session::get('personality_progress', [
                    'current_index' => 0,
                    'responses' => [],
                    'completed' => false,
                    'progress_percentage' => 0,
                    'personalityReport' => null
                ]);

                \Log::info('TestStageController: Personality progress', [
                    'progress' => $progress,
                    'session_id' => Session::getId()
                ]);

                $stageData = [
                    'progress' => $progress
                ];
                break;
        }

        return $stageData;
    }

    private function checkAllStagesCompleted()
    {
        $allProgress = [
            'holland_codes' => Session::get('holland_codes_progress', [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0
            ]),
            'basic_interests' => Session::get('basic_interest_progress', [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0
            ]),
            'degree' => Session::get('degree_progress', [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0
            ]),
            'personality' => Session::get('personality_progress', [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0
            ])
        ];

        $allCompleted = true;
        foreach ($allProgress as $progress) {
            if (!($progress['completed'] ?? false)) {
                $allCompleted = false;
                break;
            }
        }

        if ($allCompleted && Session::has('result_user')) {
            $resultTest = new ResultTest();
            return $resultTest->checkAllTestsCompleted();
        }

        return null;
    }

    public function saveResults()
    {
        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User must be authenticated'
                ], 401);
            }

            // Check if we have result_user in session
            if (!Session::has('result_user')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No test results found to save'
                ], 400);
            }

            // Check if all stages are completed
            $allProgress = [
                'holland_codes' => Session::get('holland_codes_progress', [
                    'completed' => false
                ]),
                'basic_interests' => Session::get('basic_interest_progress', [
                    'completed' => false
                ]),
                'degree' => Session::get('degree_progress', [
                    'completed' => false
                ]),
                'personality' => Session::get('personality_progress', [
                    'completed' => false
                ])
            ];

            // Debug log the progress
            \Log::info('Stage completion status:', [
                'progress' => $allProgress,
                'session_id' => Session::getId()
            ]);

            // Check if any stage is not completed
            foreach ($allProgress as $stage => $progress) {
                if (!($progress['completed'] ?? false)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stage {$stage} is not completed"
                    ], 400);
                }
            }

            $resultTest = new ResultTest();
            $result = $resultTest->checkAllTestsCompleted();

            // Convert the response to array to check the data
            $resultData = json_decode($result->getContent(), true);

            if (isset($resultData['success']) && $resultData['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Results saved successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $resultData['message'] ?? 'Failed to save results'
            ], 400);

        } catch (\Exception $e) {
            \Log::error('Failed to save test results:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session_data' => Session::get('result_user'),
                'progress_data' => $allProgress ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving your results: ' . $e->getMessage()
            ], 500);
        }
    }
}
