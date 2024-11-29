<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class TestStageController extends Controller
{
    public function changeStage(Request $request)
    {
        \Log::info('TestStageController: Starting stage change', $request->all());
        
        try {
            $validated = $request->validate([
                'fromStage' => 'required|string',
                'toStage' => 'required|string'
            ]);

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
                'testStage' => $validated['fromStage'] ?? 'holland_codes'
            ]);
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

    private function getSessionKey($stage)
    {
        return $stage . '_progress';
    }

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
} 