<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\ItemSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class PersonalityController extends BaseTestController
{
    protected const SESSION_KEY = 'personality_progress';
    protected $testStage = 'personality';
    protected $itemSets = [
        'cant_stands' => [
            'title' => 'Can\'t stands',
            'completed' => false
        ],
        'skills_preferences' => [
            'title' => 'Skills preferences',
            'completed' => false
        ]
    ];

    protected function getCurrentItemSet()
    {
        $progress = Session::get(self::SESSION_KEY, [
            'current_index' => 0,
            'responses' => [],
            'completed' => false,
            'progress_percentage' => 0,
            'validResponses' => 0,
            'cant_stands_completed' => false,
            'cant_stands_responses' => [],
            'skills_preferences_responses' => []
        ]);
        
        // If cant_stands is not completed, use it
        if (!isset($progress['cant_stands_completed']) || !$progress['cant_stands_completed']) {
            return $this->itemSets['cant_stands']['title'];
        }
        
        // If cant_stands is completed, move to skills_preferences
        return $this->itemSets['skills_preferences']['title'];
    }

    public function index()
    {
        // Session::forget(self::SESSION_KEY);
        if (!request()->header('X-Inertia')) {
            return $this->renderInitialResponse();
        }

        try {
            $progress = Session::get(self::SESSION_KEY, [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0,
                'validResponses' => 0,
                'cant_stands_completed' => false,
                'cant_stands_responses' => [],
                'skills_preferences_responses' => []
            ]);

            $currentItemSetTitle = $this->getCurrentItemSet();

            $personalityAssessment = ItemSet::with([
                'items:id,text,text_fr,help_text,option_set_id,is_completed,image_url,image_colour,itemset_id',
                'items.optionSet:id,name,help_text,type',
                'items.optionSet.options:id,text,text_fr,help_text,value,reverse_coded_value,option_set_id'
            ])
            ->where('title', $currentItemSetTitle)
            ->first();

            if (!$personalityAssessment) {
                throw new \Exception('Personality Assessment not found');
            }

            $totalQuestions = $personalityAssessment->items->count();
            $currentSetResponses = $currentItemSetTitle === $this->itemSets['cant_stands']['title'] 
                ? $progress['cant_stands_responses'] 
                : $progress['skills_preferences_responses'];

            $validResponses = count(array_filter($currentSetResponses, fn($v) => $v > 0));
            $isCompleted = $validResponses >= $totalQuestions;

            // Update progress for current item set
            if ($isCompleted) {
                if ($currentItemSetTitle === $this->itemSets['cant_stands']['title']) {
                    $progress['cant_stands_completed'] = true;
                    // Reset for next set if first set is completed
                    if (!$progress['completed']) {
                        $progress['current_index'] = 0;
                        $progress['responses'] = [];
                    }
                }
            }

            // Calculate overall completion
            $cantStandsCompleted = $progress['cant_stands_completed'];
            $skillsPreferencesResponses = count(array_filter($progress['skills_preferences_responses'], fn($v) => $v > 0));
            $skillsPreferencesTotal = ItemSet::where('title', $this->itemSets['skills_preferences']['title'])->first()->items->count();
            $skillsPreferencesCompleted = $skillsPreferencesResponses >= $skillsPreferencesTotal;

            $progress['completed'] = $cantStandsCompleted && $skillsPreferencesCompleted;
            $progress['validResponses'] = $validResponses;
            $progress['responses'] = $currentSetResponses;
            $progress['totalQuestions'] = $totalQuestions;
            $progress['progress_percentage'] = $totalQuestions > 0 
                ? min(round(($validResponses / $totalQuestions) * 100), 100)
                : 0;

            Session::put(self::SESSION_KEY, $progress);

            return $this->renderResponse($personalityAssessment, $progress, $isCompleted);

        } catch (\Exception $e) {
            return $this->handleError($e, request());
        }
    }

    protected function handleNearCompletion(array $progress): array
    {
        try {
            if ($progress['progress_percentage'] > 90) {
                $resultUser = Session::get('result_user', []);
                $resultUser['personality'] = [
                    'cant_stands' => $progress['cant_stands_responses'],
                    'skills_preferences' => $progress['skills_preferences_responses'],
                    'completed' => $progress['completed']
                ];
                Session::put('result_user', $resultUser);
            }
            return $progress;
        } catch (\Exception $e) {
            Log::error('Personality Test: Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $progress;
        }
    }

    public function storeResponse(Request $request)
    {
        try {
            $validated = $request->validate([
                'itemId' => 'required|integer',
                'answer' => 'required|integer|between:0,5',
                'type' => 'required|string|in:answered,skipped'
            ]);

            $progress = Session::get(self::SESSION_KEY, [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0,
                'validResponses' => 0,
                'cant_stands_completed' => false,
                'cant_stands_responses' => [],
                'skills_preferences_responses' => []
            ]);

            $currentItemSetTitle = $this->getCurrentItemSet();
            
            // Store response in the appropriate set
            if ($currentItemSetTitle === $this->itemSets['cant_stands']['title']) {
                $progress['cant_stands_responses'][$validated['itemId']] = 
                    $validated['type'] === 'skipped' ? 0 : $validated['answer'];
            } else {
                $progress['skills_preferences_responses'][$validated['itemId']] = 
                    $validated['type'] === 'skipped' ? 0 : $validated['answer'];
            }

            $progress['current_index']++;
            $progress['responses'] = $currentItemSetTitle === $this->itemSets['cant_stands']['title'] 
                ? $progress['cant_stands_responses'] 
                : $progress['skills_preferences_responses'];

            $personalityAssessment = ItemSet::where('title', $currentItemSetTitle)->first();
            $totalQuestions = $personalityAssessment->items->count();

            $validResponses = count(array_filter($progress['responses'], fn($v) => $v > 0));
            $progress['validResponses'] = $validResponses;
            $progress['totalQuestions'] = $totalQuestions;
            $progress['progress_percentage'] = $totalQuestions > 0 
                ? min(round(($validResponses / $totalQuestions) * 100), 100)
                : 0;

            // Check completion of current set
            if ($validResponses >= $totalQuestions) {
                if ($currentItemSetTitle === $this->itemSets['cant_stands']['title']) {
                    $progress['cant_stands_completed'] = true;
                    // Reset for next set
                    $progress['current_index'] = 0;
                    $progress['responses'] = [];
                }
            }

            // Check overall completion
            $cantStandsCompleted = $progress['cant_stands_completed'];
            $skillsPreferencesResponses = count(array_filter($progress['skills_preferences_responses'], fn($v) => $v > 0));
            $skillsPreferencesTotal = ItemSet::where('title', $this->itemSets['skills_preferences']['title'])->first()->items->count();
            $skillsPreferencesCompleted = $skillsPreferencesResponses >= $skillsPreferencesTotal;

            $progress['completed'] = $cantStandsCompleted && $skillsPreferencesCompleted;

            if ($progress['progress_percentage'] > 90) {
                $progress = $this->handleNearCompletion($progress);
            }

            Session::put(self::SESSION_KEY, $progress);

            if ($request->wantsJson()) {
                return response()->json(['progress' => $progress]);
            }

            return back()->with('progress', $progress);

        } catch (\Exception $e) {
            return $this->handleError($e, $request);
        }
    }

    public function goBack(Request $request)
    {
        try {
            $progress = Session::get(self::SESSION_KEY);
            $currentItemSetTitle = $this->getCurrentItemSet();

            if ($progress['current_index'] > 0) {
                $progress['current_index']--;
                
                // Remove last response from appropriate set
                if ($currentItemSetTitle === $this->itemSets['cant_stands']['title']) {
                    if (!empty($progress['cant_stands_responses'])) {
                        $lastItemId = array_key_last($progress['cant_stands_responses']);
                        unset($progress['cant_stands_responses'][$lastItemId]);
                    }
                    $progress['responses'] = $progress['cant_stands_responses'];
                } else {
                    if (!empty($progress['skills_preferences_responses'])) {
                        $lastItemId = array_key_last($progress['skills_preferences_responses']);
                        unset($progress['skills_preferences_responses'][$lastItemId]);
                    }
                    $progress['responses'] = $progress['skills_preferences_responses'];
                }

                $totalQuestions = ItemSet::where('title', $currentItemSetTitle)
                    ->first()
                    ->items()
                    ->count();

                $validResponses = count(array_filter($progress['responses'], fn($v) => $v > 0));
                
                $progress['validResponses'] = $validResponses;
                $progress['totalQuestions'] = $totalQuestions;
                $progress['progress_percentage'] = $totalQuestions > 0 
                    ? min(round(($validResponses / $totalQuestions) * 100), 100)
                    : 0;

                // Update completion status
                if ($currentItemSetTitle === $this->itemSets['cant_stands']['title']) {
                    $progress['cant_stands_completed'] = $validResponses >= $totalQuestions;
                }

                // Check overall completion
                $cantStandsCompleted = $progress['cant_stands_completed'];
                $skillsPreferencesResponses = count(array_filter($progress['skills_preferences_responses'], fn($v) => $v > 0));
                $skillsPreferencesTotal = ItemSet::where('title', $this->itemSets['skills_preferences']['title'])->first()->items->count();
                $skillsPreferencesCompleted = $skillsPreferencesResponses >= $skillsPreferencesTotal;

                $progress['completed'] = $cantStandsCompleted && $skillsPreferencesCompleted;
            }

            Session::put(self::SESSION_KEY, $progress);

            if ($request->wantsJson()) {
                return response()->json(['progress' => $progress]);
            }

            return back()->with('progress', $progress);

        } catch (\Exception $e) {
            return $this->handleError($e, $request);
        }
    }

    protected function renderResponse($itemSet, array $progress, bool $isCompleted = false)
    {
        return Inertia::render('Test/MainTest', [
            'personality' => [
                'id' => $itemSet->id,
                'title' => $this->getLocalizedColumn($itemSet, 'title'),
                'lead_in_text' => $this->getLocalizedColumn($itemSet, 'lead_in_text'),
                'items' => $itemSet->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'text' => $this->getLocalizedColumn($item, 'text'),
                        'help_text' => $this->getLocalizedColumn($item, 'help_text'),
                        'option_set_id' => $item->option_set_id,
                        'is_completed' => $item->is_completed,
                        'image_url' => $item->image_url,
                        'image_colour' => $item->image_colour,
                        'itemset_id' => $item->itemset_id,
                        'optionSet' => [
                            'id' => $item->optionSet->id,
                            'name' => $this->getLocalizedColumn($item->optionSet, 'name'),
                            'help_text' => $this->getLocalizedColumn($item->optionSet, 'help_text'),
                            'type' => $item->optionSet->type,
                            'options' => $item->optionSet->options->map(function ($option) {
                                return [
                                    'id' => $option->id,
                                    'text' => $this->getLocalizedColumn($option, 'text'),
                                    'help_text' => $this->getLocalizedColumn($option, 'help_text'),
                                    'value' => $option->value,
                                    'reverse_coded_value' => $option->reverse_coded_value,
                                    'option_set_id' => $option->option_set_id,
                                ];
                            })
                        ]
                    ];
                }),
                'option_sets' => $itemSet->items->pluck('optionSet')->unique()->map(function ($optionSet) {
                    return [
                        'id' => $optionSet->id,
                        'name' => $this->getLocalizedColumn($optionSet, 'name'),
                        'help_text' => $this->getLocalizedColumn($optionSet, 'help_text'),
                        'type' => $optionSet->type,
                        'options' => $optionSet->options->map(function ($option) {
                            return [
                                'id' => $option->id,
                                'text' => $this->getLocalizedColumn($option, 'text'),
                                'help_text' => $this->getLocalizedColumn($option, 'help_text'),
                                'value' => $option->value,
                                'reverse_coded_value' => $option->reverse_coded_value,
                                'option_set_id' => $option->option_set_id,
                            ];
                        })
                    ];
                })
            ],
            'progress' => $progress,
            'isCompleted' => $isCompleted,
            'testStage' => $this->testStage
        ]);
    }

    protected function renderInitialResponse()
    {
        return Inertia::render('Test/MainTest', [
            'personality' => null,
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
        Log::error('Personality Test: Error', [
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

    protected function getLocalizedColumn($model, $baseColumn)
    {
        $locale = app()->getLocale();
        $localizedColumn = $locale === 'fr' ? $baseColumn . '_fr' : $baseColumn;
        
        return $model->$localizedColumn ?? $model->$baseColumn;
    }
}