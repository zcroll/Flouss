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
    protected $itemSetTitle = 'Skills preferences'; // Start with Skills preferences

    public function index()
    {
        if (!request()->header('X-Inertia')) {
            return $this->renderInitialResponse();
        }

        try {
            ds()->queriesOn('Fetching personality test data');
            
            Log::info('Fetching personality test data', [
                'session_key' => self::SESSION_KEY,
                'test_stage' => $this->testStage
            ]);

            $progress = Session::get(self::SESSION_KEY, [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0,
                'currentSection' => 'skills_preferences',
                'sections' => [
                    'skills_preferences' => [
                        'completed' => false,
                        'responses' => []
                    ],
                    'must_haves' => [
                        'completed' => false,
                        'responses' => []
                    ],
                    'cant_stands' => [
                        'completed' => false,
                        'responses' => []
                    ]
                ],
                'validResponses' => 0,
                'totalQuestions' => 0
            ]);

            ds('Current Progress State:', $progress);

            $personalityAssessment = ItemSet::with([
                'items:id,text,text_fr,help_text,option_set_id,is_completed,image_url,image_colour,itemset_id',
                'items.optionSet:id,name,help_text,type',
                'items.optionSet.options:id,text,text_fr,help_text,value,reverse_coded_value,option_set_id'
            ])
            ->where('title', $this->itemSetTitle)
            ->first();

            ds()->queriesOff();

            if (!$personalityAssessment) {
                ds('Error: Personality Assessment not found for title:', $this->itemSetTitle);
                throw new \Exception('Personality Assessment not found');
            }

            ds('Personality Assessment:', [
                'id' => $personalityAssessment->id,
                'title' => $personalityAssessment->title,
                'items_count' => $personalityAssessment->items->count()
            ]);

            $totalQuestions = $personalityAssessment->items->count();
            $validResponses = count(array_filter($progress['responses'], fn($v) => $v > 0));
            $isCompleted = $validResponses >= $totalQuestions;

            $progress['completed'] = $isCompleted;
            $progress['validResponses'] = $validResponses;
            $progress['totalQuestions'] = $totalQuestions;
            $progress['progress_percentage'] = $totalQuestions > 0 
                ? min(round(($validResponses / $totalQuestions) * 100), 100)
                : 0;

            ds('Updated Progress:', $progress);

            Session::put(self::SESSION_KEY, $progress);

            return $this->renderResponse($personalityAssessment, $progress, $isCompleted);

        } catch (\Exception $e) {
            ds('Error in index:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->handleError($e, request());
        }
    }

    protected function handleNearCompletion(array $progress): array
    {
        try {
            ds('Checking completion:', [
                'current_title' => $this->itemSetTitle,
                'progress_percentage' => $progress['progress_percentage']
            ]);

            if ($progress['progress_percentage'] > 99) {
                ds('Section completed:', $this->itemSetTitle);
                
                // Current section is complete, move to next section
                if ($this->itemSetTitle === 'Skills preferences') {
                    $this->itemSetTitle = 'Must haves';
                    $progress['current_index'] = 0;
                    $progress['responses'] = [];
                    $progress['completed'] = false;
                    $progress['progress_percentage'] = 0;
                    $progress['validResponses'] = 0;
                    ds('Moving to Must haves section');
                } elseif ($this->itemSetTitle === 'Must haves') {
                    $this->itemSetTitle = 'Can\'t stands';
                    $progress['current_index'] = 0;
                    $progress['responses'] = [];
                    $progress['completed'] = false;
                    $progress['progress_percentage'] = 0;
                    $progress['validResponses'] = 0;
                    ds('Moving to Can\'t stands section');
                } else {
                    // All sections completed
                    $progress['completed'] = true;
                    $resultUser = Session::get('result_user', []);
                    $resultUser['personality'] = [
                        'skills_preferences' => Session::get('skills_preferences_progress'),
                        'must_haves' => Session::get('must_haves_progress'),
                        'cant_stands' => Session::get('cant_stands_progress')
                    ];
                    Session::put('result_user', $resultUser);
                    ds('All sections completed. Final results:', $resultUser['personality']);
                }
            }
            return $progress;
        } catch (\Exception $e) {
            ds('Error in handleNearCompletion:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            Log::error('Error in handleNearCompletion:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $progress;
        }
    }

    protected function renderResponse($itemSet, array $progress, bool $isCompleted = false)
    {
        // Get all items
        $items = $itemSet->items->map(function ($item) {
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
        })->values();

        ds('Items loaded:', [
            'count' => $items->count(),
            'current_index' => $progress['current_index']
        ]);

        return Inertia::render('Test/MainTest', [
            'personality' => [
                'id' => $itemSet->id,
                'title' => $this->getLocalizedColumn($itemSet, 'title'),
                'lead_in_text' => $this->getLocalizedColumn($itemSet, 'lead_in_text'),
                'items' => $items,
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
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            ds('Validation Error:', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();
        }

        Log::error('Personality Test Error:', [
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

    public function storeResponse(Request $request)
    {
        try {
            ds('Storing response:', $request->all());

            $validated = $request->validate([
                'itemId' => 'required|integer',
                'answer' => 'required|integer|between:0,5',
                'type' => 'required|string|in:answered,skipped',
                'category' => 'required|string',
                'testStage' => 'required|string'
            ]);

            ds()->queriesOn('Storing personality response');

            $progress = Session::get(self::SESSION_KEY, [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0,
                'validResponses' => 0,
                'totalQuestions' => 0
            ]);

            // Store response
            $progress['responses'][$validated['itemId']] = 
                $validated['type'] === 'skipped' ? 0 : $validated['answer'];

            $personalityAssessment = ItemSet::where('title', $this->itemSetTitle)->first();
            $totalQuestions = $personalityAssessment->items->count();

            ds()->queriesOff();

            // Move to next item
            $progress['current_index']++;

            // Calculate progress
            $validResponses = count(array_filter($progress['responses'], fn($v) => $v > 0));
            $progress['validResponses'] = $validResponses;
            $progress['totalQuestions'] = $totalQuestions;
            $progress['progress_percentage'] = $totalQuestions > 0 
                ? min(round(($validResponses / $totalQuestions) * 100), 100)
                : 0;
            $progress['completed'] = $validResponses >= $totalQuestions;

            ds('Progress after storing response:', [
                'current_index' => $progress['current_index'],
                'total_questions' => $totalQuestions,
                'valid_responses' => $validResponses,
                'percentage' => $progress['progress_percentage']
            ]);

            if ($progress['progress_percentage'] > 99) {
                $progress = $this->handleNearCompletion($progress);
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
            ds('Error in storeResponse:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->handleError($e, $request);
        }
    }

    public function goBack(Request $request)
    {
        try {
            ds('Going back from:', [
                'current_section' => $this->itemSetTitle,
                'request' => $request->all()
            ]);

            $progress = Session::get(self::SESSION_KEY, [
                'current_index' => 0,
                'responses' => [],
                'completed' => false,
                'progress_percentage' => 0,
                'currentSection' => 'skills_preferences',
                'sections' => [
                    'skills_preferences' => [
                        'completed' => false,
                        'responses' => []
                    ],
                    'must_haves' => [
                        'completed' => false,
                        'responses' => []
                    ],
                    'cant_stands' => [
                        'completed' => false,
                        'responses' => []
                    ]
                ],
                'validResponses' => 0,
                'totalQuestions' => 0
            ]);

            if ($progress['current_index'] > 0) {
                $progress['current_index']--;
                
                if (!empty($progress['responses'])) {
                    $lastItemId = array_key_last($progress['responses']);
                    unset($progress['responses'][$lastItemId]);
                    unset($progress['sections'][$progress['currentSection']]['responses'][$lastItemId]);
                    ds('Removed response:', [
                        'item_id' => $lastItemId,
                        'remaining_responses' => count($progress['responses']),
                        'remaining_section_responses' => count($progress['sections'][$progress['currentSection']]['responses'])
                    ]);
                }

                ds()->queriesOn('Fetching total questions for back navigation');

                $totalQuestions = ItemSet::where('title', $this->itemSetTitle)
                    ->first()
                    ->items()
                    ->count();

                ds()->queriesOff();

                // Calculate valid responses for current section
                $sectionResponses = array_filter($progress['sections'][$progress['currentSection']]['responses'], fn($v) => $v > 0);
                $validResponses = count($sectionResponses);
                
                $progress['validResponses'] = $validResponses;
                $progress['totalQuestions'] = $totalQuestions;
                $progress['progress_percentage'] = $totalQuestions > 0 
                    ? min(round(($validResponses / $totalQuestions) * 100), 100)
                    : 0;

                // Update section completion status
                $progress['sections'][$progress['currentSection']]['completed'] = $validResponses >= $totalQuestions;
                $progress['completed'] = $validResponses >= $totalQuestions;

                ds('Updated progress after going back:', $progress);
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
            ds('Error in goBack:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->handleError($e, $request);
        }
    }

    protected function getLocalizedColumn($model, $baseColumn)
    {
        $locale = app()->getLocale();
        $localizedColumn = $locale === 'fr' ? $baseColumn . '_fr' : $baseColumn;
        
        return $model->$localizedColumn ?? $model->$baseColumn;
    }
} 