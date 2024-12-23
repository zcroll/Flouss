<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use App\Models\ItemSet;

abstract class BaseTestController extends Controller
{
    protected $sessionKey;
    protected $testStage;
    protected $itemSetTitle;
    
    protected function getInitialProgress(): array
    {
        return [
            'current_index' => 0,
            'responses' => [],
            'completed' => false,
            'progress_percentage' => 0,
            'validResponses' => 0
        ];
    }

    public function index()
    {
        if (!request()->header('X-Inertia')) {
            return $this->renderInitialResponse();
        }

        try {
            $progress = Session::get($this->sessionKey, $this->getInitialProgress());
            $itemSet = $this->getItemSet();

            if (!$itemSet) {
                throw new \Exception("No {$this->testStage} items found");
            }

            $totalQuestions = $itemSet->items->count();
            $validResponses = $this->countValidResponses($progress['responses']);
            $isCompleted = $validResponses >= $totalQuestions;

            // Update progress
            $progress['completed'] = $isCompleted;
            $progress['validResponses'] = $validResponses;
            Session::put($this->sessionKey, $progress);

            return $this->renderResponse($itemSet, $progress, $isCompleted);
        } catch (\Exception $e) {
            \Log::error("{$this->testStage}: Error in index", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->renderError($e->getMessage());
        }
    }

    public function storeResponse(Request $request)
    {
        try {
            $validated = $this->validateRequest($request);
            $progress = Session::get($this->sessionKey, $this->getInitialProgress());
            $itemSet = $this->getItemSet();
            $totalQuestions = $itemSet->items->count();

            // Store response
            $progress['responses'][$validated['itemId']] = 
                $validated['type'] === 'skipped' ? 0 : $validated['answer'];

            // Update progress
            $progress['current_index']++;
            $validResponses = $this->countValidResponses($progress['responses']);
            $progress['validResponses'] = $validResponses;
            $progress['progress_percentage'] = $this->calculateProgress($validResponses, $totalQuestions);
            $progress['completed'] = $validResponses >= $totalQuestions;

            // Handle additional processing (e.g., job matching, archetype discovery)
            if ($progress['progress_percentage'] > 90) {
                $progress = $this->handleNearCompletion($progress);
            }

            Session::put($this->sessionKey, $progress);

            if ($request->wantsJson()) {
                return response()->json(['progress' => $progress]);
            }

            return $this->renderResponse($itemSet, $progress);
        } catch (\Exception $e) {
            return $this->handleError($e, $request);
        }
    }

    public function goBack(Request $request)
    {
        try {
            $progress = Session::get($this->sessionKey, $this->getInitialProgress());
            $itemSet = $this->getItemSet();
            $totalQuestions = $itemSet->items->count();

            if ($progress['current_index'] > 0) {
                $progress['current_index']--;
                
                if (!empty($progress['responses'])) {
                    $lastItemId = array_key_last($progress['responses']);
                    unset($progress['responses'][$lastItemId]);
                }

                $validResponses = $this->countValidResponses($progress['responses']);
                $progress['validResponses'] = $validResponses;
                $progress['progress_percentage'] = $this->calculateProgress($validResponses, $totalQuestions);
                $progress['completed'] = $validResponses >= $totalQuestions;
            }

            Session::put($this->sessionKey, $progress);

            if ($request->wantsJson()) {
                return response()->json(['progress' => $progress]);
            }

            return back()->with([
                'progress' => $progress,
                'testStage' => $this->testStage
            ]);
        } catch (\Exception $e) {
            return $this->handleError($e, $request);
        }
    }

    protected function getItemSet()
    {
        return ItemSet::with([
            'items:id,text,text_fr,help_text,option_set_id,is_completed,career_id,degree_id,image_url,image_colour,itemset_id',
            'items.optionSet:id,name,help_text,type',
            'items.optionSet.options:id,text,text_fr,help_text,value,reverse_coded_value,option_set_id'
        ])
        ->where('title', $this->itemSetTitle)
        ->first();
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'itemId' => 'required|integer',
            'answer' => 'required|integer|between:0,5',
            'type' => 'required|string|in:answered,skipped',
            'category' => 'required|string',
            'testStage' => 'required|string'
        ]);
    }

    protected function countValidResponses(array $responses): int
    {
        return count(array_filter($responses, fn($answer) => $answer > 0));
    }

    protected function calculateProgress(int $validResponses, int $totalQuestions): int
    {
        return $totalQuestions > 0 ? min(round(($validResponses / $totalQuestions) * 100), 100) : 0;
    }

    abstract protected function handleNearCompletion(array $progress): array;
    abstract protected function renderResponse($itemSet, array $progress, bool $isCompleted = false);
    abstract protected function renderInitialResponse();
    abstract protected function renderError(string $message);
    abstract protected function handleError(\Exception $e, Request $request);
} 