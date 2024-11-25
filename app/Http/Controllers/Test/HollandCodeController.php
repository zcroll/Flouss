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
                'testStage' => $hollandCodes->title
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
            ds(['progress' => $progress]);

            // Store response in exactly the same format as received
            $progress['responses'][$validated['itemId']] = [
                'itemId' => $validated['itemId'],
                'answer' => $validated['answer'],
                'type' => $validated['type'],
                'category' => $validated['category'],
                'testStage' => $validated['testStage']
            ];

            // Increment current index
            $progress['current_index']++;

            // Check if test is complete
            $hollandCodes = ItemSet::with([
                'items:id,text,help_text,option_set_id,is_completed,career_id,degree_id,image_url,image_colour,itemset_id',
                'items.optionSet:id,name,help_text,type',
                'items.optionSet.options:id,text,help_text,value,reverse_coded_value,option_set_id'
            ])
            ->where('title', 'Hollands codes')
            ->first();

            $isComplete = $progress['current_index'] >= $hollandCodes->items->count();
            ds(['isComplete' => $isComplete]);
           
            $progress['progress_percentage'] = min(32, round(($progress['current_index'] / $hollandCodes->items->count()) * 100));
            $progress['completed'] = $isComplete;

            if ($isComplete) {
                try {
                    $archetypeResults = $this->formatResponsesWithTraits($progress);
                    // Get archetype discovery details
                    $archetype = $archetypeResults[1]['archetypes'];
                    $archetypeDiscovery = DB::table('archetype_discoveries')
                        ->where('slug', '=', strtolower($archetype[0]))
                        ->first();
                    if ($archetypeDiscovery) {
                        $progress['archetypeDiscovery'] = $archetypeDiscovery;
                    } else {
                        \Log::warning('Archetype discovery not found for: ' . $archetype[0]);
                        $progress['archetypeDiscovery'] = null;
                    }

                    
                    ds(['archetypeResults' => $archetypeResults]);
                    if (!empty($archetypeResults)) {
                        $progress['archetypeResults'] = $archetypeResults;
                    } else {
                        \Log::warning('Empty archetype results returned');
                        $progress['archetypeResults'] = [];
                    }
                } catch (\Exception $e) {
                    \Log::error('Error processing archetype results: ' . $e->getMessage());
                    $progress['archetypeResults'] = [];
                }
            }

            Session::put('holland_code_progress', $progress);

            \Log::info('Stored response:', $progress['responses'][$validated['itemId']]);

            ds(['progress' => $progress]);
         
            // Return Inertia response with updated data
            return Inertia::render('Test/MainTest', [
                'progress' => $progress,
                'progress_percentage' => $progress['progress_percentage'],
                'completed' => $isComplete,
                'currentIndex' => $progress['current_index'],
                'testStage' => 'holland_codes',
                'hollandCodes' => $isComplete ? null : [
                    'id' => $hollandCodes->id,
                    'type' => $hollandCodes->type,
                    'title' => $hollandCodes->title,
                    'lead_in_text' => $hollandCodes->lead_in_text,
                    'items' => $hollandCodes->items,
                    'option_sets' => $hollandCodes->items->map(function($item) {
                        return $item->optionSet;
                    })->unique()
                  ],
                  'archetypeDiscovery' => $progress['archetypeDiscovery']
                
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
        $archetypeResults = $this->getArchetypeAndTopScores($normalizedScores);
        
        return [$formattedResponses, $archetypeResults];
    }


    public function goBack()
    {
        if (!request()->header('X-Inertia')) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        try {
            $progress = Session::get('holland_code_progress');
            
            if ($progress && $progress['current_index'] > 0) {
                $progress['current_index']--;
                Session::put('holland_code_progress', $progress);
            }

            // Fetch Holland Codes data for the updated state
            $hollandCodes = ItemSet::where('title', 'Hollands codes')->first();

            return Inertia::render('Test/MainTest', [
                'progress' => $progress,
                'testStage' => 'holland_codes',
                'hollandCodes' => [
                    'id' => $hollandCodes->id,
                    'type' => $hollandCodes->type,
                    'title' => $hollandCodes->title,
                    'lead_in_text' => $hollandCodes->lead_in_text,
                    'items' => $hollandCodes->items,
                    'option_sets' => $hollandCodes->items->pluck('optionSet')->unique()
                ]
            ]);
        } catch (\Exception $e) {
            return Inertia::render('Test/MainTest', [
                'error' => 'Failed to go back',
                'testStage' => 'holland_codes'
            ]);
        }
    }
}
