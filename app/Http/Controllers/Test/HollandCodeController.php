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

class HollandCodeController extends BaseTestController
{
    use ArchetypeFinder;

    protected $sessionKey = 'holland_codes_progress';
    protected $testStage = 'holland_codes';
    protected $itemSetTitle = 'Hollands codes';

    protected function handleNearCompletion(array $progress): array
    {
        $formattedResponses = [];
        foreach ($progress['responses'] as $itemId => $answer) {
            if ($answer > 0) {
                $formattedResponses[$itemId] = [
                    'itemId' => $itemId,
                    'answer' => $answer,
                    'type' => 'answered',
                    'category' => 'holland_codes'
                ];
            }
        }
        
        if (!empty($formattedResponses)) {
            $formattedProgress = ['responses' => $formattedResponses];
            $formattedResponses = $this->formatResponsesWithTraits($formattedProgress);
            ds($formattedResponses);
            $archetypeResults = $this->getArchetypeAndTopScores($formattedResponses['scores']);
          

            if (!empty($archetypeResults) && isset($archetypeResults['archetypes'][0])) {
                Session::put('result_user', [
                    'archetype' => $archetypeResults,
                    'scores' => $formattedResponses['scores']
                ]);
                
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

        return $progress;
    }

    protected function renderResponse($itemSet, array $progress, bool $isCompleted = false)
    {
        return Inertia::render('Test/MainTest', [
            'hollandCodes' => [
                'id' => $itemSet->id,
                'type' => $itemSet->type,
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
            'hollandCodes' => null,
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
        \Log::error('Holland Codes Error:', [
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
        
        return $formattedResponses;
    }
}
