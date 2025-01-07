<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\ItemSet;
use App\Models\JobInfo;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BasicInterestController extends BaseTestController
{
    protected $sessionKey = 'basic_interest_progress';
    protected $testStage = 'basic_interests';
    protected $itemSetTitle = 'Self Reported Interests';

    protected function handleNearCompletion(array $progress): array
    {
        $formattedResponses = [];
        foreach ($progress['responses'] as $itemId => $answer) {
            if ($answer > 0) {
                $formattedResponses[$itemId] = $answer;
            }
        }
        
        if (!empty($formattedResponses)) {
            $data = $this->formatResponse($formattedResponses);
            $pythonJobResults = $this->matchJobs($data);
            
            if (isset($pythonJobResults['job_matches'])) {
                $jobIds = array_column($pythonJobResults['job_matches'], 'job_id');
                $cacheKey = "job_matches_" . implode('_', $jobIds);
                $jobs = cache()->remember($cacheKey, now()->addHours(24), function () use ($jobIds) {
                    return JobInfo::whereIn('id', $jobIds)->get();
                });
                $progress['jobMatching'] = $jobs->take(5);
                $resultUser = Session::get('result_user', []);
                $resultUser['jobs'] = $jobs;
                Session::put('result_user', $resultUser);
            }
        }

        return $progress;
    }

    protected function renderResponse($itemSet, array $progress, bool $isCompleted = false)
    {
        return Inertia::render('Test/MainTest', [
            'basicInterest' => [
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
            'basicInterest' => null,
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
        Log::error('Basic Interest Error:', [
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

    protected function formatResponse($progress)
    {
        $items = Item::whereIn('id', array_keys($progress))->pluck('text', 'id');
        
        $formattedResponses = [];
        foreach ($progress as $itemId => $response) {
            $name = $items[$itemId] ?? 'Unknown';
            $formattedResponses[$name] = (int) $response;
        }

        return $formattedResponses;
    }

    private function matchJobs($interest_scores)
    {
        $pythonPath = 'python3';
        $scriptPath = app_path('/python/test.py');
        
        try {
            $process = new Process([
                $pythonPath,
                $scriptPath,
                json_encode($interest_scores)
            ]);

            $process->setTimeout(300);
            $process->run();

            if (!$process->isSuccessful()) {
                Log::error('Python script failed', ['error' => $process->getErrorOutput()]);
                throw new ProcessFailedException($process);
            }

            return json_decode($process->getOutput(), true);

        } catch (\Exception $e) {
            Log::error('Job matching failed', ['error' => $e->getMessage()]);
            return ['error' => 'Failed to process job matching'];
        }
    }
} 