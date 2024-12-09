<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

/**
 * Class TestStageController
 * 
 * Manages the test stage transitions and response storage for the assessment system.
 * This controller acts as a coordinator between different test stages (Holland Codes,
 * Basic Interests, Workplace, and Personality assessments).
 *
 * @package App\Http\Controllers\Test
 */
class TestStageController extends Controller
{
    /**
     * Session key used to store the current test stage
     * 
     * @var string
     */
    const TEST_STAGE_SESSION_KEY = 'current_test_stage';

    /**
     * Changes the current test stage after validating completion of the previous stage
     *
     * @param Request $request Contains fromStage and toStage parameters
     * @return \Inertia\Response
     */
    public function changeStage(Request $request)
    {
        \Log::info('TestStageController: Starting stage change', $request->all());

        try {
            $validated = $request->validate([
                'fromStage' => 'required|string',
                'toStage' => 'required|string'
            ]);

            Session::put(self::TEST_STAGE_SESSION_KEY, $validated['toStage']);

            \Log::info('TestStageController: Validated request', $validated);

            // Get the session key from the appropriate controller
            $sessionKey = $this->getSessionKey($validated['fromStage']);

            // Verify previous stage completion
            $fromStageProgress = Session::get($sessionKey, [
                'completed' => false
            ]);

            \Log::info('TestStageController: Previous stage progress', [
                'stage' => $validated['fromStage'],
                'progress' => $fromStageProgress,
                'sessionKey' => $sessionKey
            ]);

            if (!$fromStageProgress['completed']) {
                \Log::warning('TestStageController: Previous stage not completed', [
                    'stage' => $validated['fromStage'],
                    'progress' => $fromStageProgress,
                    'sessionKey' => $sessionKey
                ]);

                return Inertia::render('Test/MainTest', [
                    'error' => 'Previous stage not completed',
                    'testStage' => $validated['fromStage']
                ]);
            }

            // Get data for the next stage
            \Log::info('TestStageController: Getting next stage controller', [
                'stage' => $validated['toStage']
            ]);

            $controller = $this->getStageController($validated['toStage']);

            // Instead of trying to extract props, just let the next stage's controller handle the rendering
            return $controller->index();

        } catch (\Exception $e) {
            \Log::error('TestStageController: Stage transition failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return Inertia::render('Test/MainTest', [
                'error' => 'Stage transition failed: ' . $e->getMessage(),
                'testStage' => Session::get(self::TEST_STAGE_SESSION_KEY, 'holland_codes')
            ]);
        }
    }

    /**
     * Stores a user's response for the current test stage
     *
     * @param Request $request Contains response data including itemId, answer, type, category and testStage
     * @return \Inertia\Response
     */
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
            return $controller->storeResponse($request);

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

    /**
     * Generates a session key for storing progress of a specific test stage
     *
     * @param string $stage The test stage name
     * @return string The session key
     */
    private function getSessionKey($stage)
    {
        return $stage . '_progress';
    }

    /**
     * Returns the appropriate controller instance for a given test stage
     *
     * @param string $stage The test stage name
     * @return Controller|null Returns null if stage is invalid
     */
    protected function getStageController($stage)
    {
        switch ($stage) {
            case 'holland_codes':
                return new HollandCodeController();
            case 'basic_interests':
                return new BasicInterestController();
            case 'workplace':
                return new WorkplaceController();
            case 'personality':
                return new PersonalityController();
            default:
                return null;
        }
    }

    /**
     * Retrieves the current test stage from the session
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentStage()
    {
        return response()->json([
            'currentStage' => Session::get(self::TEST_STAGE_SESSION_KEY, 'holland_codes')
        ]);
    }
}
