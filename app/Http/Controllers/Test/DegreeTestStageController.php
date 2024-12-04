<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\ItemSet;
use App\Models\DegreeMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class DegreeTestStageController extends BaseTestController
{
    protected const SESSION_KEY = 'degree_progress';
    protected $testStage = 'degree';
    protected $itemSetTitle = 'Hollands codes';

    protected function handleNearCompletion(array $progress): array
    {
        try {
            if ($progress['progress_percentage'] > 90) {
                $degreeMatches = $this->calculateDegreeMatches($progress['responses']);
                if (!empty($degreeMatches)) {
                    $progress['degreeMatching'] = $degreeMatches;
                }
            }
            return $progress;
        } catch (\Exception $e) {
            \Log::error('DegreeTestStage: Error in handleNearCompletion', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $progress;
        }
    }

    protected function renderResponse($itemSet, array $progress, bool $isCompleted = false)
    {
        return Inertia::render('Test/MainTest', [
            'degree' => [
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
            'degree' => null,
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
        \Log::error('DegreeTestStage: Error', [
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

    public function index()
    {
        if (!request()->header('X-Inertia')) {
            return $this->renderInitialResponse();
        }

        try {
            $progress = Session::get(self::SESSION_KEY, [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0,
                'validResponses' => 0
            ]);

            $degreeAssessment = ItemSet::with([
                'items:id,text,help_text,option_set_id,is_completed,career_id,degree_id,image_url,image_colour,itemset_id',
                'items.optionSet:id,name,help_text,type',
                'items.optionSet.options:id,text,help_text,value,reverse_coded_value,option_set_id'
            ])
            ->where('title', $this->itemSetTitle)
            ->first();

            if (!$degreeAssessment) {
                throw new \Exception('Degree Assessment not found');
            }

            $totalQuestions = $degreeAssessment->items->count();
            $validResponses = count(array_filter($progress['responses'], fn($v) => $v > 0));
            $isCompleted = $validResponses >= $totalQuestions;

            $progress['completed'] = $isCompleted;
            $progress['validResponses'] = $validResponses;
            $progress['totalQuestions'] = $totalQuestions;
            $progress['progress_percentage'] = $totalQuestions > 0 
                ? min(round(($validResponses / $totalQuestions) * 100), 100)
                : 0;

            Session::put(self::SESSION_KEY, $progress);

            return $this->renderResponse($degreeAssessment, $progress, $isCompleted);

        } catch (\Exception $e) {
            return $this->handleError($e, request());
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

            $progress = Session::get(self::SESSION_KEY, [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0,
                'validResponses' => 0
            ]);

            $progress['responses'][$validated['itemId']] = 
                $validated['type'] === 'skipped' ? 0 : $validated['answer'];

            $degreeAssessment = ItemSet::where('title', $this->itemSetTitle)->first();
            $totalQuestions = $degreeAssessment->items->count();

            $progress['current_index']++;

            $validResponses = count(array_filter($progress['responses'], fn($v) => $v > 0));
            $progress['validResponses'] = $validResponses;
            $progress['totalQuestions'] = $totalQuestions;
            $progress['progress_percentage'] = $totalQuestions > 0 
                ? min(round(($validResponses / $totalQuestions) * 100), 100)
                : 0;
            $progress['completed'] = $validResponses >= $totalQuestions;

            if ($progress['progress_percentage'] > 90) {
                $degreeMatches = $this->calculateDegreeMatches($progress['responses']);
                if (!empty($degreeMatches)) {
                    $progress['degreeMatching'] = $degreeMatches;
                }
            }

            Session::put(self::SESSION_KEY, $progress);

            if ($request->wantsJson()) {
                return response()->json(['progress' => $progress]);
            }

            return back()->with([
                'progress' => $progress,
                'testStage' => $this->testStage
            ]);

        } catch (\Exception $e) {
            \Log::error('DegreeTestStage: Error storing response', [
                'message' => $e->getMessage(),
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
                'progress_percentage' => 0,
                'validResponses' => 0
            ]);

            if ($progress['current_index'] > 0) {
                $progress['current_index']--;
                
                if (!empty($progress['responses'])) {
                    $lastItemId = array_key_last($progress['responses']);
                    unset($progress['responses'][$lastItemId]);
                }

                $totalQuestions = ItemSet::where('title', $this->itemSetTitle)
                    ->first()
                    ->items()
                    ->count();

                $validResponses = count(array_filter($progress['responses'], fn($v) => $v > 0));
                $progress['validResponses'] = $validResponses;
                $progress['progress_percentage'] = $totalQuestions > 0 
                    ? min(round(($validResponses / $totalQuestions) * 100), 100)
                    : 0;
                $progress['completed'] = $validResponses >= $totalQuestions;
            }

            Session::put(self::SESSION_KEY, $progress);

            if ($request->wantsJson()) {
                return response()->json(['progress' => $progress]);
            }

            return back()->with([
                'progress' => $progress,
                'testStage' => $this->testStage
            ]);

        } catch (\Exception $e) {
            \Log::error('DegreeTestStage: Error going back', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to go back: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function calculateDegreeMatches(array $responses)
    {
        try {
            $items = \App\Models\Item::whereIn('id', array_keys($responses))
                ->with('degreeCategory')
                ->get();

            $categoryScores = [];
            foreach ($items as $item) {
                $category = $item->degreeCategory->name ?? 'uncategorized';
                if (!isset($categoryScores[$category])) {
                    $categoryScores[$category] = [
                        'total' => 0,
                        'count' => 0
                    ];
                }
                $categoryScores[$category]['total'] += $responses[$item->id];
                $categoryScores[$category]['count']++;
            }

            $averageScores = [];
            foreach ($categoryScores as $category => $data) {
                if ($data['count'] > 0) {
                    $averageScores[$category] = $data['total'] / $data['count'];
                }
            }
            arsort($averageScores);

            return DegreeMatch::whereIn('category', array_keys($averageScores))
                ->orderByRaw('FIELD(category, ' . implode(',', array_keys($averageScores)) . ')')
                ->limit(5)
                ->get();

        } catch (\Exception $e) {
            \Log::error('DegreeTestStage: Error calculating matches', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }
} 