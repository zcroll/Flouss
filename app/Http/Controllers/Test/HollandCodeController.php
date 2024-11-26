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
            $progress = Session::get('holland_code_progress', [
                'current_index' => 0,
                'responses' => []
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
            ds($progress['current_index'] >= $totalQuestions);

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
                'isCompleted' => $progress['current_index'] >= $totalQuestions

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
        if (!$request->header('X-Inertia')) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        try {
            $validated = $request->validate([
                'itemId' => 'required|integer',
                'answer' => 'required|integer|between:0,5',
                'type' => 'required|string|in:answered,skipped',
                'category' => 'required|string',
                'testStage' => 'required|string'
            ]);

            // Get current progress from session
            $progress = Session::get('holland_code_progress', [
                'current_index' => 0,
                'responses' => [],
                'completed' => false
            ]);

            // Store response
            $progress['responses'][$validated['itemId']] = [
                'itemId' => $validated['itemId'],
                'answer' => $validated['answer'],
                'type' => $validated['type'],
                'category' => $validated['category'],
                'testStage' => $validated['testStage']
            ];

            // Increment current index
            $progress['current_index']++;

            // Get total questions count
            $hollandCodes = ItemSet::where('title', 'Hollands codes')->first();
            $totalQuestions = $hollandCodes->items->count();
            
            // Calculate accurate progress percentage
            $progress['progress_percentage'] = round(($progress['current_index'] / $totalQuestions) * 100);
            
            // Check if test is complete
            $progress['completed'] = $progress['current_index'] >= $totalQuestions;
            ds($progress['progress_percentage']);
            if ($progress['progress_percentage'] > 70) {
                $formattedResponses = $this->formatResponsesWithTraits($progress);
                $archetypeResults = $this->getArchetypeAndTopScores($formattedResponses['scores']);

                ds($archetypeResults);
                if (!empty($archetypeResults) && isset($archetypeResults['archetypes'][0])) {
                    $progress['archetypeResults'] = $archetypeResults;
                    // Get archetype discovery details if test is complete
                    $archetypeName = $archetypeResults['archetypes'][0];
                    ds($archetypeName);
                    $archetypeDiscovery = DB::table('archetype_discoveries')
                        ->where('slug', '=', strtolower($archetypeName))
                        ->first();
                    if ($archetypeDiscovery) {
                        $progress['archetypeDiscovery'] = $archetypeDiscovery;
                        ds($progress['archetypeDiscovery']);
                    }
                }
            }

            Session::put('holland_code_progress', $progress);

            return Inertia::render('Test/MainTest', [
                'progress' => $progress,
                'testStage' => $hollandCodes->title
            ]);

        } catch (\Exception $e) {
            \Log::error('Error storing response: ' . $e->getMessage());
            return Inertia::render('Test/MainTest', [
                'error' => 'Failed to store response: ' . $e->getMessage(),
                'testStage' => 'holland_codes'
            ]);
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
            if ($response['type'] === 'skipped') {
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
            
            $traitScores[$traitType] += (int)$response['answer'];
            $traitCounts[$traitType]++;
            
            if (!isset($formattedResponses[$traitType])) {
                $formattedResponses[$traitType] = [];
            }
            
            $formattedResponses[$traitType][] = [
                'itemId' => $itemId,
                'answer' => $response['answer'],
                'type' => $response['type'],
                'category' => $response['category']
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


    public function goBack(Request $request)
    {
        if (!$request->header('X-Inertia')) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        try {
            // Get current progress from session
            $progress = Session::get('holland_code_progress', [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0
            ]);

            // Validate the current index is greater than 0
            if ($progress['current_index'] > 0) {
                // Get the current item ID that we're removing
                $hollandCodes = ItemSet::where('title', 'Hollands codes')->first();
                $currentItemId = $hollandCodes->items[$progress['current_index']]->id ?? null;

                if ($currentItemId && isset($progress['responses'][$currentItemId])) {
                    // Remove the current response
                    unset($progress['responses'][$currentItemId]);
                }

                // Decrement the index
                $progress['current_index']--;

                // Recalculate progress percentage
                $totalItems = $hollandCodes->items->count();
                $answeredItems = count($progress['responses']);
                $progress['progress_percentage'] = min(32, round(($answeredItems / $totalItems) * 100));

                // Update completion status
                $progress['completed'] = $progress['current_index'] >= $totalItems;

                // Store updated progress in session
                Session::put('holland_code_progress', $progress);

                // Log the progress update
                \Log::info('Progress updated after going back:', [
                    'current_index' => $progress['current_index'],
                    'responses_count' => count($progress['responses']),
                    'progress_percentage' => $progress['progress_percentage']
                ]);

                // Return Inertia response instead of JSON
                return Inertia::render('Test/MainTest', [
                    'progress' => $progress,
                    'hollandCodes' => [
                        'id' => $hollandCodes->id,
                        'type' => $hollandCodes->type,
                        'title' => $hollandCodes->title,
                        'lead_in_text' => $hollandCodes->lead_in_text,
                        'items' => $hollandCodes->items,
                        'option_sets' => $hollandCodes->items->pluck('optionSet')->unique()
                    ],
                'testStage' => $hollandCodes->title
                ]);
            }

            // Return Inertia error response
            return Inertia::render('Test/MainTest', [
                'error' => 'Already at the first question',
                'testStage' => 'holland_codes'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in goBack:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return Inertia error response
            return Inertia::render('Test/MainTest', [
                'error' => 'Failed to go back: ' . $e->getMessage(),
                'testStage' => 'holland_codes'
            ]);
        }
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
