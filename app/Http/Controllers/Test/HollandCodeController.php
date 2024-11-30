<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\ItemSet;
use App\Models\QuestionTrait;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Traits\ArchetypeFinder;

class HollandCodeController extends Controller
{
    use ArchetypeFinder;

    protected const SESSION_KEY = 'holland_codes_progress';

    public function index()
    {
        // Initial page load without X-Inertia header
        if (!request()->header('X-Inertia')) {
            return Inertia::render('Test/MainTest', [
                'hollandCodes' => null,
                'progress' => null,
                'testStage' => 'holland_codes'
            ]);
        }

        try {
            // Get progress from session
            $progress = Session::get(self::SESSION_KEY, [
                'current_index' => 0,
                'responses' => [],
                'completed' => false
            ]);

            // Fetch Holland Codes data only for Inertia requests
            $hollandCodes = ItemSet::with([
                'items:id,text,help_text,option_set_id,is_completed,career_id,degree_id,image_url,image_colour,itemset_id',
                'items.optionSet:id,name,help_text,type',
                'items.optionSet.options:id,text,help_text,value,reverse_coded_value,option_set_id'
            ])
            ->where('title', 'Hollands codes')
            ->first();

            if (!$hollandCodes) {
                return Inertia::render('Test/MainTest', [
                    'error' => 'No Holland Code items found',
                    'testStage' => 'holland_codes'
                ]);
            }

            $totalQuestions = $hollandCodes->items->count();
            $isCompleted = $progress['current_index'] >= $totalQuestions;
            
            // Update the completed flag in the session
            $progress['completed'] = $isCompleted;
            Session::put(self::SESSION_KEY, $progress);

            return Inertia::render('Test/MainTest', [
                'hollandCodes' => [
                    'id' => $hollandCodes->id,
                    'type' => $hollandCodes->type,
                    'title' => $hollandCodes->title,
                    'lead_in_text' => $hollandCodes->lead_in_text,
                    'items' => $hollandCodes->items,
                    'option_sets' => $hollandCodes->items->pluck('optionSet')->unique()
                ],
                'progress' => $progress,
                'testStage' => $hollandCodes->title,
                'isCompleted' => $isCompleted
            ]);

        } catch (\Exception $e) {
            return Inertia::render('Test/MainTest', [
                'error' => 'An error occurred while fetching Holland Codes',
                'testStage' => 'holland_codes'
            ]);
        }
    }

    public function storeResponse(Request $request)
    {
        try {
            $validated = $request->validate([
                'itemId' => 'required|integer',
                'answer' => 'required|integer|between:0,5', // Allow 0 for skipped questions
                'type' => 'required|string|in:answered,skipped',
                'category' => 'required|string',
                'testStage' => 'required|string'
            ]);

            \Log::info('Received response:', $validated);

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
            $hollandCodes = ItemSet::where('title', 'Hollands codes')->first();
            $totalQuestions = $hollandCodes->items->count();

            // Increment current index
            $progress['current_index']++;

            // Calculate progress based on current index
            $progress['progress_percentage'] = $totalQuestions > 0 
                ? round(($progress['current_index'] / $totalQuestions) * 100) 
                : 0;

            // Update completed status
            $isCompleted = $progress['current_index'] >= $totalQuestions;
            $progress['completed'] = $isCompleted;

            \Log::info('Storing response:', [
                'currentIndex' => $progress['current_index'],
                'totalQuestions' => $totalQuestions,
                'percentage' => $progress['progress_percentage'],
                'type' => $validated['type'],
                'answer' => $validated['answer'],
                'storedAnswer' => $progress['responses'][$validated['itemId']],
                'completed' => $isCompleted
            ]);

            // Calculate archetype if progress is sufficient
            if ($progress['progress_percentage'] > 90) {
                // Only include non-skipped responses for archetype calculation
                $formattedResponses = [];
                foreach ($progress['responses'] as $itemId => $answer) {
                    if ($answer > 0) { // Skip answers with value 0
                        $formattedResponses[$itemId] = [
                            'itemId' => $itemId,
                            'answer' => $answer,
                            'type' => 'answered', // Force type to answered for archetype calculation
                            'category' => $validated['category']
                        ];
                    }
                }
                
                if (!empty($formattedResponses)) {
                    $formattedProgress = ['responses' => $formattedResponses];
                    $formattedResponses = $this->formatResponsesWithTraits($formattedProgress);
                    $archetypeResults = $this->getArchetypeAndTopScores($formattedResponses['scores']);

                    if (!empty($archetypeResults) && isset($archetypeResults['archetypes'][0])) {
                        $progress['archetypeResults'] = $archetypeResults;
                        
                        $archetypeName = $archetypeResults['archetypes'][0];
                        $archetypeDiscovery = DB::table('archetype_discoveries')
                            ->where('slug', '=', strtolower($archetypeName))
                            ->first();
                            
                        if ($archetypeDiscovery) {
                            $progress['archetypeDiscovery'] = $archetypeDiscovery;
                        }
                    }
                }
            }

            Session::put(self::SESSION_KEY, $progress);

            if ($request->wantsJson()) {
                return response()->json(['progress' => $progress]);
            }

            return back()->with([
                'progress' => $progress,
                'testStage' => $hollandCodes->title
            ]);

        } catch (\Exception $e) {
            \Log::error('Error storing response: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Failed to store response: ' . $e->getMessage()
            ], 500);
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

            // Get total questions count
            $hollandCodes = ItemSet::where('title', 'Hollands codes')->first();
            $totalQuestions = $hollandCodes->items->count();

            // Only decrement if greater than 0
            if ($progress['current_index'] > 0) {
                $progress['current_index']--;
            }

            // Calculate progress based on current index
            $progress['progress_percentage'] = $totalQuestions > 0 
                ? round(($progress['current_index'] / $totalQuestions) * 100) 
                : 0;

            // Update completed status
            $progress['completed'] = $progress['current_index'] >= $totalQuestions;

            \Log::info('Going back:', [
                'currentIndex' => $progress['current_index'],
                'totalQuestions' => $totalQuestions,
                'percentage' => $progress['progress_percentage']
            ]);

            Session::put(self::SESSION_KEY, $progress);

            if ($request->wantsJson()) {
                return response()->json(['progress' => $progress]);
            }

            return back()->with([
                'progress' => $progress,
                'testStage' => $hollandCodes->title
            ]);

        } catch (\Exception $e) {
            \Log::error('Error going back: ' . $e->getMessage());
            return back()->with('error', 'Failed to go back: ' . $e->getMessage());
        }
    }

    protected function formatResponsesWithTraits($progress)
    {
        $formattedResponses = [];
        $traitScores = [];
        $traitCounts = [];
        
        $traitNames = [
            'A' => 'Artistic',
            'C' => 'Conventional', 
            'I' => 'Investigative',
            'S' => 'Social',
            'E' => 'Enterprising',
            'R' => 'Realistic'
        ];
        
        foreach ($progress['responses'] as $itemId => $response) {
            if (is_array($response) && isset($response['type']) && $response['type'] === 'skipped') {
                continue;
            }
            
            $trait = QuestionTrait::where('question_id', $itemId)->first();
            if (!$trait) {
                \Log::warning("No trait found for question ID: {$itemId}");
                continue;
            }
            
            $traitType = $trait->holland_trait;
            
            if (!isset($traitScores[$traitType])) {
                $traitScores[$traitType] = 0;
                $traitCounts[$traitType] = 0;
            }
            
            $answer = is_array($response) ? $response['answer'] : $response;
            $traitScores[$traitType] += (int)$answer;
            $traitCounts[$traitType]++;
            
            if (!isset($formattedResponses[$traitType])) {
                $formattedResponses[$traitType] = [];
            }
            
            $formattedResponses[$traitType][] = [
                'itemId' => $itemId,
                'answer' => $answer,
                'type' => is_array($response) ? $response['type'] : 'answered',
                'category' => is_array($response) ? $response['category'] : 'holland_codes'
            ];
        }
        
        $normalizedScores = [];
        foreach ($traitScores as $trait => $score) {
            if ($traitCounts[$trait] > 0 && isset($traitNames[$trait])) {
                $normalizedScore = round($score / ($traitCounts[$trait] * 5), 2);
                $normalizedScores[$traitNames[$trait]] = $normalizedScore;
            }
        }
        
        if (empty($normalizedScores)) {
            \Log::error('No valid scores calculated in formatResponsesWithTraits');
            return [[], []];
        }
        
        $formattedResponses['scores'] = $normalizedScores;
        
        // Get archetype results using the trait finder
        
        return  $formattedResponses;
    }


    public function handleRequest(Request $request)
    {
        if (!$request->header('X-Inertia')) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        try {
            // Handle different actions based on request type
            $action = $request->input('action');
            
            switch ($action) {
                case 'store-response':
                    return $this->storeResponse($request);
                case 'go-back':
                    return $this->goBack($request);
                default:
                    return $this->index();
            }
        } catch (\Exception $e) {
            \Log::error('Error handling request:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Inertia::render('Test/MainTest', [
                'error' => 'An error occurred: ' . $e->getMessage(),
                'testStage' => 'holland_codes'
            ]);
        }
    }
}
