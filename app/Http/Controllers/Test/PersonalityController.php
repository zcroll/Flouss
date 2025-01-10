<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\ItemSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class PersonalityController extends BaseTestController
{
    protected const SESSION_KEY = 'personality_progress';
    protected $testStage = 'personality';
    protected $itemSets = [
        'cant_stands' => [
            'title' => 'Can\'t stands',
            'completed' => false
        ],
        'must_haves' => [
            'title' => 'Must haves',
            'completed' => false
        ],
        'skills_preferences' => [
            'title' => 'Skills preferences',
            'completed' => false
        ]
    ];

    protected function getCurrentItemSet(): string
    {
        $progress = Session::get(self::SESSION_KEY, $this->getInitialProgress());
        
        // Check sequence: cant_stands -> must_haves -> skills_preferences
        if (!isset($progress['cant_stands_completed']) || !$progress['cant_stands_completed']) {
            return $this->itemSets['cant_stands']['title'];
        }
        
        if (!isset($progress['must_haves_completed']) || !$progress['must_haves_completed']) {
            return $this->itemSets['must_haves']['title'];
        }
        
        return $this->itemSets['skills_preferences']['title'];
    }

    protected function calculateTotalProgress(array $progress): int
    {
        $cantStandsResponses = count(array_filter($progress['cant_stands_responses'] ?? [], fn($v) => $v > 0));
        $mustHavesResponses = count(array_filter($progress['must_haves_responses'] ?? [], fn($v) => $v > 0));
        $skillsResponses = count(array_filter($progress['skills_preferences_responses'] ?? [], fn($v) => $v > 0));
        
        // Calculate percentage based on total of 37 questions
        return min(round((($cantStandsResponses + $mustHavesResponses + $skillsResponses) / 37) * 100), 100);
    }

    public function index(): Response
    {
        if (!request()->header('X-Inertia')) {
            return $this->renderInitialResponse();
        }
    // Session::forget(self::SESSION_KEY);

        try {
            $progress = Session::get(self::SESSION_KEY, $this->getInitialProgress());
            $currentItemSetTitle = $this->getCurrentItemSet();

            $cacheKey = "personality_assessment_{$currentItemSetTitle}";
            $personalityAssessment = Cache::remember($cacheKey, now()->addHours(24), function () use ($currentItemSetTitle) {
                return ItemSet::with([
                    'items:id,text,text_fr,help_text,option_set_id,is_completed,image_url,image_colour,itemset_id',
                    'items.optionSet:id,name,help_text,type',
                    'items.optionSet.options:id,text,text_fr,help_text,value,reverse_coded_value,option_set_id'
                ])
                ->where('title', $currentItemSetTitle)
                ->first();
            });

            if (!$personalityAssessment) {
                throw new \Exception('Personality Assessment not found');
            }

            $totalQuestionsForSet = $personalityAssessment->items->count();
            $currentSetResponses = match($currentItemSetTitle) {
                $this->itemSets['cant_stands']['title'] => $progress['cant_stands_responses'],
                $this->itemSets['must_haves']['title'] => $progress['must_haves_responses'],
                default => $progress['skills_preferences_responses']
            };

            $validResponses = count(array_filter($currentSetResponses, fn($v) => $v > 0));
            $isCompleted = $validResponses >= $totalQuestionsForSet;

            // Update progress for current item set
            if ($isCompleted) {
                if ($currentItemSetTitle === $this->itemSets['cant_stands']['title']) {
                    $progress['cant_stands_completed'] = true;
                    if (!$progress['completed']) {
                        $progress['current_index'] = 0;
                        $progress['responses'] = [];
                    }
                } elseif ($currentItemSetTitle === $this->itemSets['must_haves']['title']) {
                    $progress['must_haves_completed'] = true;
                    if (!$progress['completed']) {
                        $progress['current_index'] = 0;
                        $progress['responses'] = [];
                    }
                }
            }

            // Calculate overall completion
            $cantStandsCompleted = $progress['cant_stands_completed'];
            $mustHavesCompleted = $progress['must_haves_completed'];
            $skillsPreferencesResponses = count(array_filter($progress['skills_preferences_responses'], fn($v) => $v > 0));
            $skillsPreferencesCompleted = $skillsPreferencesResponses >= $totalQuestionsForSet;

            $progress['completed'] = $cantStandsCompleted && $mustHavesCompleted && $skillsPreferencesCompleted;
            $progress['validResponses'] = $validResponses;
            $progress['responses'] = $currentSetResponses;
            $progress['totalQuestions'] = 37; // Set total questions to 37
            $progress['progress_percentage'] = $this->calculateTotalProgress($progress);

            Session::put(self::SESSION_KEY, $progress);

            return $this->renderResponse($personalityAssessment, $progress, $isCompleted);

        } catch (\Exception $e) {
            return $this->handleError($e, request());
        }
    }

    protected function getInitialProgress(): array
    {
        return [
            'current_index' => 0,
            'responses' => [],
            'completed' => false,
            'progress_percentage' => 0,
            'validResponses' => 0,
            'totalQuestions' => 37, // Set total questions to 37
            'cant_stands_completed' => false,
            'cant_stands_responses' => [],
            'must_haves_completed' => false,
            'must_haves_responses' => [],
            'skills_preferences_completed' => false,
            'skills_preferences_responses' => []
        ];
    }

    protected function formatResponses(array $progress): array
    {
        $formattedResponses = [
            'must_haves' => [],
            'skills_preferences' => [],
            'cant_stands' => []
        ];

        // Format Must Haves responses
        foreach ($progress['must_haves_responses'] ?? [] as $itemId => $value) {
            if ($value > 0) {
                $formattedResponses['must_haves'][$itemId] = $value;  // Example: 486 => 5  // Mathematical skills
            }
        }

        // Format Skills Preferences responses
        foreach ($progress['skills_preferences_responses'] ?? [] as $itemId => $value) {
            if ($value > 0) {
                $formattedResponses['skills_preferences'][$itemId] = $value;  // Example: 1308 => 5 // Analytical thinking
            }
        }

        // Format Can't Stands responses
        foreach ($progress['cant_stands_responses'] ?? [] as $itemId => $value) {
            if ($value > 0) {
                $formattedResponses['cant_stands'][$itemId] = $value;  // Example: 492 => 4  // Helping people
            }
        }

        return $formattedResponses;
    }

    protected function handleNearCompletion(array $progress): array
    {
        try {
            if ($progress['progress_percentage'] > 80) {
                $resultUser = Session::get('result_user', []);
                $resultUser['personality'] = $this->formatResponses($progress);
                ds($resultUser['personality']);
                $resultUser['personality']['completed'] = $progress['completed'];
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

    public function storeResponse(Request $request): Response
    {
        try {
            $validated = $request->validate([
                'itemId' => 'required|integer',
                'answer' => 'required|integer|between:0,5',
                'type' => 'required|string|in:answered,skipped'
            ]);

            $progress = Session::get(self::SESSION_KEY, $this->getInitialProgress());
            $currentItemSetTitle = $this->getCurrentItemSet();
            ds(['progress logic'=>$progress]);

            
            // Store response in the appropriate set
            if ($currentItemSetTitle === $this->itemSets['cant_stands']['title']) {
                $progress['cant_stands_responses'][$validated['itemId']] = 
                    $validated['type'] === 'skipped' ? 0 : $validated['answer'];
            } elseif ($currentItemSetTitle === $this->itemSets['must_haves']['title']) {
                $progress['must_haves_responses'][$validated['itemId']] = 
                    $validated['type'] === 'skipped' ? 0 : $validated['answer'];
            } else {
                $progress['skills_preferences_responses'][$validated['itemId']] = 
                    $validated['type'] === 'skipped' ? 0 : $validated['answer'];
            }

            $progress['current_index']++;
            $progress['responses'] = $currentItemSetTitle === $this->itemSets['cant_stands']['title'] 
                ? $progress['cant_stands_responses'] 
                : ($currentItemSetTitle === $this->itemSets['must_haves']['title'] 
                    ? $progress['must_haves_responses'] 
                    : $progress['skills_preferences_responses']);

            $cacheKey = "personality_assessment_{$currentItemSetTitle}";
            $personalityAssessment = Cache::remember($cacheKey, now()->addHours(24), function () use ($currentItemSetTitle) {
                return ItemSet::where('title', $currentItemSetTitle)->first();
            });

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
                } elseif ($currentItemSetTitle === $this->itemSets['must_haves']['title']) {
                    $progress['must_haves_completed'] = true;
                    // Reset for next set
                    $progress['current_index'] = 0;
                    $progress['responses'] = [];
                }
            }

            // Check overall completion
            $cantStandsCompleted = $progress['cant_stands_completed'];
            $mustHavesCompleted = $progress['must_haves_completed'];
            $skillsPreferencesTotal = Cache::remember('skills_preferences_total', now()->addHours(24), function () {
                return ItemSet::where('title', $this->itemSets['skills_preferences']['title'])
                    ->first()
                    ->items
                    ->count();
            });

            $skillsPreferencesResponses = count(array_filter($progress['skills_preferences_responses'], fn($v) => $v > 0));
            $skillsPreferencesCompleted = $skillsPreferencesResponses >= $skillsPreferencesTotal;

            $progress['completed'] = $cantStandsCompleted && $mustHavesCompleted && $skillsPreferencesCompleted;

            if ($progress['progress_percentage'] > 90) {
                $progress = $this->handleNearCompletion($progress);
            }

            Session::put(self::SESSION_KEY, $progress);

            return Inertia::render('Test/MainTest', [
                'progress' => $progress
            ]);

        } catch (\Exception $e) {
            return $this->handleError($e, $request);
        }
    }

    public function goBack(Request $request): Response
    {
        try {
            $progress = Session::get(self::SESSION_KEY, $this->getInitialProgress());
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
                } elseif ($currentItemSetTitle === $this->itemSets['must_haves']['title']) {
                    if (!empty($progress['must_haves_responses'])) {
                        $lastItemId = array_key_last($progress['must_haves_responses']);
                        unset($progress['must_haves_responses'][$lastItemId]);
                    }
                    $progress['responses'] = $progress['must_haves_responses'];
                } else {
                    if (!empty($progress['skills_preferences_responses'])) {
                        $lastItemId = array_key_last($progress['skills_preferences_responses']);
                        unset($progress['skills_preferences_responses'][$lastItemId]);
                    }
                    $progress['responses'] = $progress['skills_preferences_responses'];
                }

                $cacheKey = "personality_assessment_{$currentItemSetTitle}_count";
                $totalQuestions = Cache::remember($cacheKey, now()->addHours(24), function () use ($currentItemSetTitle) {
                    return ItemSet::where('title', $currentItemSetTitle)
                        ->first()
                        ->items()
                        ->count();
                });

                $validResponses = count(array_filter($progress['responses'], fn($v) => $v > 0));
                
                $progress['validResponses'] = $validResponses;
                $progress['totalQuestions'] = $totalQuestions;
                $progress['progress_percentage'] = $totalQuestions > 0 
                    ? min(round(($validResponses / $totalQuestions) * 100), 100)
                    : 0;

                // Update completion status
                if ($currentItemSetTitle === $this->itemSets['cant_stands']['title']) {
                    $progress['cant_stands_completed'] = $validResponses >= $totalQuestions;
                } elseif ($currentItemSetTitle === $this->itemSets['must_haves']['title']) {
                    $progress['must_haves_completed'] = $validResponses >= $totalQuestions;
                }

                // Check overall completion
                $cantStandsCompleted = $progress['cant_stands_completed'];
                $mustHavesCompleted = $progress['must_haves_completed'];
                $skillsPreferencesTotal = Cache::remember('skills_preferences_total', now()->addHours(24), function () {
                    return ItemSet::where('title', $this->itemSets['skills_preferences']['title'])
                        ->first()
                        ->items
                        ->count();
                });

                $skillsPreferencesResponses = count(array_filter($progress['skills_preferences_responses'], fn($v) => $v > 0));
                $skillsPreferencesCompleted = $skillsPreferencesResponses >= $skillsPreferencesTotal;

                $progress['completed'] = $cantStandsCompleted && $mustHavesCompleted && $skillsPreferencesCompleted;
            }

            Session::put(self::SESSION_KEY, $progress);

            return Inertia::render('Test/MainTest', [
                'progress' => $progress
            ]);

        } catch (\Exception $e) {
            return $this->handleError($e, $request);
        }
    }

    protected function renderResponse($itemSet, array $progress, bool $isCompleted = false): Response
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

    protected function renderInitialResponse(): Response
    {
        return Inertia::render('Test/MainTest', [
            'personality' => null,
            'progress' => null,
            'testStage' => $this->testStage
        ]);
    }

    protected function renderError(string $message): Response
    {
        return Inertia::render('Test/MainTest', [
            'error' => $message,
            'testStage' => $this->testStage
        ]);
    }

    protected function handleError(\Exception $e, Request $request): Response
    {
        Log::error('Personality Test: Error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return $this->renderError($e->getMessage());
    }

    protected function getLocalizedColumn($model, $baseColumn)
    {
        $locale = app()->getLocale();
        $localizedColumn = $locale === 'fr' ? $baseColumn . '_fr' : $baseColumn;
        
        return $model->$localizedColumn ?? $model->$baseColumn;
    }
}