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
use log;


class BasicInterestController extends Controller
{
    protected const SESSION_KEY = 'basic_interest_progress';

    public function index()
    {
        // \Log::info('BasicInterestController: Starting index method');
        
        // if (!request()->header('X-Inertia')) {
        //     \Log::info('BasicInterestController: Non-Inertia request');
        //     return Inertia::render('Test/MainTest', [
        //         'basicInterest' => null,
        //         'progress' => null,
        //         'testStage' => 'basic_interest'
        //     ]);
        // }

        try {
            $progress = Session::get(self::SESSION_KEY, [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0
            ]);
            
            \Log::info('BasicInterestController: Progress loaded', $progress);

            $basicInterest = ItemSet::with([
                'items:id,text,help_text,option_set_id,itemset_id',
                'items.optionSet:id,name,help_text,type',
                'items.optionSet.options:id,text,help_text,value,option_set_id'
            ])
            ->where('title', 'Self Reported Interests')
            ->first();

            if (!$basicInterest) {
                \Log::error('BasicInterestController: No Basic Interest ItemSet found');
                throw new \Exception('Basic Interest ItemSet not found');
            }

            $totalQuestions = $basicInterest->items->count();
            $isCompleted = $progress['current_index'] >= $totalQuestions;

            \Log::info('BasicInterestController: Total questions count', ['count' => $totalQuestions]);

            if ($totalQuestions === 0) {
                \Log::error('BasicInterestController: No questions found in Basic Interest ItemSet');
                throw new \Exception('No questions found in Basic Interest ItemSet');
            }

            ds(['iscomplated'=>$progress['completed']]);

            $response = [
                'basicInterest' => [
                    'id' => $basicInterest->id,
                    'title' => $basicInterest->title,
                    'items' => $basicInterest->items,
                    'lead_in_text' => $basicInterest->lead_in_text,
                    'option_sets' => $basicInterest->items->pluck('optionSet')->unique(),
                    'total_questions' => $totalQuestions
                ],
                'progress' => $progress,
                'isCompleted' => $isCompleted,
                'testStage' => 'basic_interest'
            ];

            \Log::info('BasicInterestController: Returning response', array_merge(
                ['testStage' => $response['testStage']],
                ['hasBasicInterest' => isset($response['basicInterest'])],
                ['totalQuestions' => $totalQuestions]
            ));

            return Inertia::render('Test/MainTest', $response);

        } catch (\Exception $e) {
            \Log::error('BasicInterestController: Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Inertia::render('Test/MainTest', [
                'error' => 'Error loading Basic Interest test: ' . $e->getMessage(),
                'testStage' => 'basic_interest'
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

            \Log::info('BasicInterestController: Received response:', $validated);

            // Get current progress from session
            $progress = Session::get(self::SESSION_KEY, [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0
            ]); 

            // Store response (0 for skipped, actual value for answered)
            $progress['responses'][$validated['itemId']] = $validated['type'] === 'skipped' 
                ? 0 
                : $validated['answer'];

            // Get total questions count
            $basicInterest = ItemSet::where('title', 'Self Reported Interests')->first();
            $totalQuestions = $basicInterest->items->count();

            // Increment current index
            $progress['current_index']++;
            

            // \Log::info('BasicInterestController: Progress updated:', $progress);

            // Get completion status using the getter method
            $completionStatus = $this->getCompletionStatus($progress, $totalQuestions);
            
            // Update progress with completion status
            $progress = array_merge($progress, $completionStatus);

            \Log::info('BasicInterestController: Storing response:', [
                'currentIndex' => $progress['current_index'],
                'totalQuestions' => $totalQuestions,
                'validResponses' => $progress['validResponses'],
                'percentage' => $progress['progress_percentage'],
                'isComplete' => $progress['isComplete'],
                'type' => $validated['type'],
                'answer' => $validated['answer'],
                'storedAnswer' => $progress['responses'][$validated['itemId']]
            ]);

            if ($progress['progress_percentage'] > 90) {
                // Only include non-skipped responses for job matching
                $formattedResponses = [];
                foreach ($progress['responses'] as $itemId => $answer) {
                    if ($answer > 0) { // Skip answers with value 0
                        $formattedResponses[$itemId] = $answer;
                    }
                }
                
                if (!empty($formattedResponses)) {
                    $data = $this->formatResponse($formattedResponses);
                    \Log::info('BasicInterestController: Formatted responses:', $data);
                    $pythonJobResults = $this->matchJobs($data);

                    // Extract job IDs from Python results
                    $jobIds = array_column($pythonJobResults['job_matches'], 'job_id');

                    // Get the actual job records from database
                    $job = JobInfo::whereIn('id', $jobIds)->get();
                    $progress['jobMatching'] = $job;
                }
            }

            Session::put(self::SESSION_KEY, $progress);

            // Get complete basic interest data for the response
            $basicInterest = ItemSet::with([
                'items:id,text,help_text,option_set_id,is_completed,career_id,degree_id,image_url,image_colour,itemset_id',
                'items.optionSet:id,name,help_text,type',
                'items.optionSet.options:id,text,help_text,value,reverse_coded_value,option_set_id'
            ])
            ->where('title', 'Self Reported Interests')
            ->first();

            if ($request->wantsJson()) {
                return response()->json(['progress' => $progress]);
            }

            return Inertia::render('Test/MainTest', [
                'basicInterest' => [
                    'id' => $basicInterest->id,
                    'title' => $basicInterest->title,
                    'lead_in_text' => $basicInterest->lead_in_text,
                    'items' => $basicInterest->items,
                    'option_sets' => $basicInterest->items->pluck('optionSet')->unique()
                ],
                'progress' => $progress,
                'testStage' => $basicInterest->title,
            ]);
        } catch (\Exception $e) {
            \Log::error('BasicInterestController: Error storing response', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Inertia::render('Test/MainTest', [
                'error' => 'Failed to store response: ' . $e->getMessage(),
                'testStage' => 'basic_interests'
            ]);
        }
    }

    public function goBack(Request $request)
    {
        try {
            $progress = Session::get(self::SESSION_KEY, [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0
            ]);

            // Get total questions count from the database
            $basicInterest = ItemSet::with('items')->where('title', 'Self Reported Interests')->first();
            if (!$basicInterest) {
                throw new \Exception('Basic Interest ItemSet not found');
            }
            $totalQuestions = $basicInterest->items->count();

            // Remove the last response if any exist
            if (!empty($progress['responses'])) {
                $lastItemId = array_key_last($progress['responses']);
                unset($progress['responses'][$lastItemId]);
            }

            // Update current index to match the number of responses
            $progress['current_index'] = count($progress['responses']);

            // Calculate progress based on number of responses
            $progress['progress_percentage'] = $totalQuestions > 0 
                ? round((count($progress['responses']) / $totalQuestions) * 100) 
                : 0;

            // Update completed status based on number of responses matching total questions
            $progress['completed'] = count($progress['responses']) >= $totalQuestions;

            \Log::info('BasicInterestController: Going back', [
                'currentIndex' => $progress['current_index'],
                'totalQuestions' => $totalQuestions,
                'responseCount' => count($progress['responses']),
                'percentage' => $progress['progress_percentage'],
                'completed' => $progress['completed']
            ]);

            Session::put(self::SESSION_KEY, $progress);

            if ($request->wantsJson()) {
                return response()->json(['progress' => $progress]);
            }

            return back()->with([
                'progress' => $progress,
                'testStage' => 'basic_interest'
            ]);

        } catch (\Exception $e) {
            \Log::error('BasicInterestController: Error going back', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Inertia::render('Test/MainTest', [
                'error' => 'Failed to go back: ' . $e->getMessage(),
                'testStage' => 'basic_interest'
            ]);
        }
    }


    protected function getCompletionStatus($progress, $totalQuestions)
    {
        // Count valid responses (non-skipped answers)
        $validResponses = count(array_filter($progress['responses'], function($answer) {
            return $answer > 0;
        }));

        return [
            'validResponses' => $validResponses,
            'totalQuestions' => $totalQuestions,
            'isComplete' => $validResponses >= ($totalQuestions - 1), // Allow one skip
            'progress_percentage' => $totalQuestions > 0 
                ? round(($progress['current_index'] / $totalQuestions) * 100) 
                : 0
        ];
    }

    public function formatResponse($progress)
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
                Log::error('Python script failed', ['error' => $process->getErrorOutput()]);
                throw new ProcessFailedException($process);
            }

            return json_decode($process->getOutput(), true);

        } catch (\Exception $e) {
            Log::error('Job matching failed', ['error' => $e->getMessage()]);
            return ['error' => 'Failed to process job matching'];
        }
    }
} 