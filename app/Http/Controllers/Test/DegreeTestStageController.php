<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\ItemSet;
use App\Models\DegreeMatch;
use App\Models\Degree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class DegreeTestStageController extends BaseTestController
{
    protected const SESSION_KEY = 'degree_progress';
    protected $testStage = 'degree';
    protected $itemSetTitle = 'can\'t stands';

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
            Log::error('DegreeTestStage: Error in handleNearCompletion', [
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
                'title' => $this->getLocalizedColumn($itemSet, 'title'),
                'lead_in_text' => $this->getLocalizedColumn($itemSet, 'lead_in_text'),
                'items' => $itemSet->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'text' => $this->getLocalizedColumn($item, 'text'),
                        'help_text' => $this->getLocalizedColumn($item, 'help_text'),
                        'option_set_id' => $item->option_set_id,
                        'is_completed' => $item->is_completed,
                        'career_id' => $item->career_id,
                        'degree_id' => $item->degree_id,
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

    protected function getLocalizedColumn($model, $baseColumn)
    {
        $locale = app()->getLocale();
        $localizedColumn = $locale === 'fr' ? $baseColumn . '_fr' : $baseColumn;
        
        return $model->$localizedColumn ?? $model->$baseColumn;
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
        Log::error('DegreeTestStage: Error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

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

            $cacheKey = "degree_assessment_{$this->itemSetTitle}";
            $degreeAssessment = Cache::remember($cacheKey, now()->addHours(24), function () {
                return ItemSet::with([
                    'items:id,text,text_fr,help_text,option_set_id,is_completed,career_id,degree_id,image_url,image_colour,itemset_id',
                    'items.optionSet:id,name,help_text,type',
                    'items.optionSet.options:id,text,text_fr,help_text,value,reverse_coded_value,option_set_id'
                ])
                ->where('title', $this->itemSetTitle)
                ->first();
            });

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

            $cacheKey = "degree_assessment_{$this->itemSetTitle}";
            $degreeAssessment = Cache::remember($cacheKey, now()->addHours(24), function () {
                return ItemSet::where('title', $this->itemSetTitle)->first();
            });

            $totalQuestions = $degreeAssessment->items->count();
            $progress['current_index']++;

            $validResponses = count(array_filter($progress['responses'], fn($v) => $v > 0));
            $progress['validResponses'] = $validResponses;
            $progress['totalQuestions'] = $totalQuestions;
            $progress['progress_percentage'] = $totalQuestions > 0 
                ? min(round(($validResponses / $totalQuestions) * 100), 100)
                : 0;
            $progress['completed'] = $validResponses >= $totalQuestions;

            if ($progress['progress_percentage'] > 99) {
                $degreeMatches = $this->calculateDegreeMatches();
                if (!empty($degreeMatches)) {
                    $progress['degreeMatching'] = array_slice($degreeMatches, 0, 5);
                    $resultUser = Session::get('result_user', []);
                    $resultUser['degreeMatching'] = $degreeMatches;
                    Session::put('result_user', $resultUser);
                }
            }

            Session::put(self::SESSION_KEY, $progress);

            return Inertia::render('Test/MainTest', [
                'degree' => [
                    'id' => $degreeAssessment->id,
                    'items' => $degreeAssessment->items
                ],
                'progress' => $progress,
                'testStage' => $this->testStage
            ]);

        } catch (\Exception $e) {
            Log::error('DegreeTestStage: Error storing response', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->renderError($e->getMessage());
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

                $cacheKey = "degree_assessment_{$this->itemSetTitle}";
                $totalQuestions = Cache::remember($cacheKey . '_count', now()->addHours(24), function () {
                    return ItemSet::where('title', $this->itemSetTitle)
                        ->first()
                        ->items()
                        ->count();
                });

                $validResponses = count(array_filter($progress['responses'], fn($v) => $v > 0));
                $progress['validResponses'] = $validResponses;
                $progress['progress_percentage'] = $totalQuestions > 0 
                    ? min(round(($validResponses / $totalQuestions) * 100), 100)
                    : 0;
                $progress['completed'] = $validResponses >= $totalQuestions;
            }

            Session::put(self::SESSION_KEY, $progress);

            return Inertia::render('Test/MainTest', [
                'progress' => $progress,
                'testStage' => $this->testStage
            ]);

        } catch (\Exception $e) {
            Log::error('DegreeTestStage: Error going back', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->renderError($e->getMessage());
        }
    }

    protected function calculateDegreeMatches()
    {
        try {
            $jobMatches = Session::get('result_user')['jobs'];
            
            // Extract job IDs from job matches
            $jobIds = collect($jobMatches)->pluck('id')->toArray();

            $cacheKey = "degree_matches_" . implode('_', $jobIds);
            return Cache::remember($cacheKey, now()->addHours(24), function () use ($jobIds) {
                // Get degrees related to these jobs through DegreeJobsRelation
                return Degree::whereHas('degreeJobsRelation', function($query) use ($jobIds) {
                    $query->whereIn('job_id', $jobIds);
                })
                ->select('id', 'name', 'slug', 'degree_level', 'salary', 'image')
                ->get()
                ->map(function($degree) {
                    return [        
                        'id' => $degree->id,
                        'name' => $degree->name,
                        'slug' => $degree->slug,
                        'degree_level' => $degree->degree_level,
                        'salary' => $degree->salary,
                        'image' => $degree->image
                    ];
                })
                ->toArray();
            });

        } catch (\Exception $e) {
            Log::error('DegreeTestStage: Error calculating matches', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }
} 