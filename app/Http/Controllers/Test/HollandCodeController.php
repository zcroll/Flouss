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
use Illuminate\Support\Facades\Log;

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

        Session::put($this->sessionKey, $progress);
        return $progress;
    }

    protected function renderResponse($itemSet, array $progress, bool $isCompleted = false)
    {
        // Debug the locale first
        // ds([
        //     'debug_locale' => [
        //         'current_locale' => app()->getLocale(),
        //         'is_french' => app()->getLocale() === 'fr'
        //     ]
        // ]);

        // Add static translations for common option texts
        $optionTranslations = [
            'Hate it' => 'Je déteste ça',
            'Dislike it' => 'Je n\'aime pas ça',
            'Neutral' => 'Neutre',
            'Like it' => 'J\'aime ça',
            'Love it' => 'J\'adore',
            'Smiley faces with "Hate it" to "Love it" (1 to 5)' => 'Visages souriants de "Je déteste" à "J\'adore" (1 à 5)'
        ];

        // Debug a sample item to verify text fields
        // $sampleItem = $itemSet->items->first();
        // ds([
        //     'sample_item_debug' => [
        //         'text' => $sampleItem->text,
        //         'text_fr' => $sampleItem->text_fr,
        //         'current_locale' => app()->getLocale()
        //     ]
        // ]);

        return Inertia::render('Test/MainTest', [
            'hollandCodes' => [
                'id' => $itemSet->id,
                'type' => $itemSet->type,
                'title' => $this->getLocalizedColumn($itemSet, 'title'),
                'lead_in_text' => $this->getLocalizedColumn($itemSet, 'lead_in_text'),
                'items' => $itemSet->items->map(function ($item) use ($optionTranslations) {
                    // Get the localized text for the item
                    $itemText = app()->getLocale() === 'fr' && !empty($item->text_fr) 
                        ? $item->text_fr 
                        : $item->text;

                    $mappedItem = [
                        'id' => $item->id,
                        'text' => $itemText,
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
                            'name' => app()->getLocale() === 'fr' 
                                ? $optionTranslations[$item->optionSet->name] ?? $item->optionSet->name 
                                : $item->optionSet->name,
                            'help_text' => $this->getLocalizedColumn($item->optionSet, 'help_text'),
                            'type' => $item->optionSet->type,
                            'options' => $item->optionSet->options->map(function ($option) use ($optionTranslations) {
                                $text = $option->text;
                                if (app()->getLocale() === 'fr') {
                                    $text = $option->text_fr ?? $optionTranslations[$option->text] ?? $option->text;
                                }
                                
                                return [
                                    'id' => $option->id,
                                    'text' => $text,
                                    'help_text' => $this->getLocalizedColumn($option, 'help_text'),
                                    'value' => $option->value,
                                    'reverse_coded_value' => $option->reverse_coded_value,
                                    'option_set_id' => $option->option_set_id,
                                ];
                            })
                        ]
                    ];

                    // // Debug the item text localization
                    // ds([
                    //     'item_text_debug' => [
                    //         'id' => $item->id,
                    //         'original_text' => $item->text,
                    //         'french_text' => $item->text_fr,
                    //         'selected_text' => $itemText,
                    //         'locale' => app()->getLocale()
                    //     ]
                    // ]);

                    return $mappedItem;
                }),
                'option_sets' => $itemSet->items->pluck('optionSet')->unique()->map(function ($optionSet) use ($optionTranslations) {
                    $mappedOptionSet = [
                        'id' => $optionSet->id,
                        'name' => app()->getLocale() === 'fr' 
                            ? $optionTranslations[$optionSet->name] ?? $optionSet->name 
                            : $optionSet->name,
                        'help_text' => $this->getLocalizedColumn($optionSet, 'help_text'),
                        'type' => $optionSet->type,
                        'options' => $optionSet->options->map(function ($option) use ($optionTranslations) {
                            $text = $option->text;
                            if (app()->getLocale() === 'fr') {
                                $text = $option->text_fr ?? $optionTranslations[$option->text] ?? $option->text;
                            }
                            
                            return [
                                'id' => $option->id,
                                'text' => $text,
                                'help_text' => $this->getLocalizedColumn($option, 'help_text'),
                                'value' => $option->value,
                                'reverse_coded_value' => $option->reverse_coded_value,
                                'option_set_id' => $option->option_set_id,
                            ];
                        })
                    ];

                    // Debug the mapped option set
                    // ds([
                    //     'mapped_option_set_debug' => [
                    //         'id' => $mappedOptionSet['id'],
                    //         'name' => $mappedOptionSet['name'],
                    //         'first_option' => $mappedOptionSet['options']->first(),
                    //         'locale' => app()->getLocale()
                    //     ]
                    // ]);

                    return $mappedOptionSet;
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
        Log::error('Holland Codes Error:', [
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
                Log::warning("No trait found for question ID: {$itemId}");
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
            Log::error('No valid scores calculated in formatResponsesWithTraits');
            return [[], []];
        }
        
        $formattedResponses['scores'] = $normalizedScores;
        
        return $formattedResponses;
    }

    protected function getLocalizedColumn($model, $baseColumn)
    {
        $locale = app()->getLocale();
        $localizedColumn = $locale === 'fr' ? $baseColumn . '_fr' : $baseColumn;
        
        return $model->$localizedColumn ?? $model->$baseColumn;
    }
}
