<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\ItemSet;
use App\Models\JobInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BasicInterestController extends BaseTestController
{
    protected $sessionKey = 'basic_interest_progress';
    protected $testStage = 'basic_interests';
    protected $itemSetTitle = 'Self Reported Interests';

    protected function handleNearCompletion(array $progress): array
    {
        $formattedResponses = [];
        foreach ($progress['responses'] as $itemId => $answer) {
            if ($answer > 0) {
                $formattedResponses[$itemId] = $answer;
            }
        }
        
        if (!empty($formattedResponses)) {
            $data = $this->formatResponse($formattedResponses);
            $pythonJobResults = $this->matchJobs($data);
            
            if (isset($pythonJobResults['job_matches'])) {
                $jobIds = array_column($pythonJobResults['job_matches'], 'job_id');
                $jobs = JobInfo::whereIn('id', $jobIds)->get();
                $progress['jobMatching'] = $jobs;
            }
        }

        return $progress;
    }

    protected function renderResponse($itemSet, array $progress, bool $isCompleted = false)
    {
        return Inertia::render('Test/MainTest', [
            'basicInterest' => [
                'id' => $itemSet->id,
                'title' => $itemSet->title,
                'lead_in_text' => $itemSet->lead_in_text,
                'items' => $itemSet->items,
                'option_sets' => $itemSet->items->pluck('optionSet')->unique()
            ],
            'progress' => $progress,
            'isCompleted' => $isCompleted,
            'testStage' => $this->testStage
        ]);
    }

    protected function renderInitialResponse()
    {
        return Inertia::render('Test/MainTest', [
            'basicInterest' => null,
            'progress' => null,
            'testStage' => $this->testStage
        ]);
    }

    protected function renderError(string $message)
    {
        return Inertia::render('Test/MainTest', [
            'error' => $message,
            'testStage' => $this->testStage
        ]);
    }

    protected function handleError(\Exception $e, Request $request)
    {
        \Log::error('Basic Interest Error:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'error' => 'Failed to process request: ' . $e->getMessage()
            ], 500);
        }

        return $this->renderError($e->getMessage());
    }

    protected function formatResponse($progress)
    {
        $items = \App\Models\Item::whereIn('id', array_keys($progress))->pluck('text', 'id');
        
        $formattedResponses = [];
        foreach ($progress as $itemId => $response) {
            $name = $items[$itemId] ?? 'Unknown';
            $formattedResponses[$name] = (int) $response;
        }

        return $formattedResponses;
    }

    private function matchJobs($interest_scores)
    {
        $pythonPath = 'python3';
        $scriptPath = app_path('/python/test.py');
        
        try {
            $process = new Process([
                $pythonPath,
                $scriptPath,
                json_encode($interest_scores)
            ]);

            $process->setTimeout(300);
            $process->run();

            if (!$process->isSuccessful()) {
                \Log::error('Python script failed', ['error' => $process->getErrorOutput()]);
                throw new ProcessFailedException($process);
            }

            return json_decode($process->getOutput(), true);

        } catch (\Exception $e) {
            \Log::error('Job matching failed', ['error' => $e->getMessage()]);
            return ['error' => 'Failed to process job matching'];
        }
    }
} 