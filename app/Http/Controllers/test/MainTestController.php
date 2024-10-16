<?php

namespace App\Http\Controllers\test;

use App\Http\Controllers\Controller;
use App\Models\HollandCodeSet;
use App\Models\HollandCodeItem;
use App\Models\BasicInterest;
use App\Models\Result;
use App\Http\Requests\MainTest\StoreResponseRequest;
use App\Traits\CalculatesScores;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use App\Traits\ArchetypeFinder;

class MainTestController extends Controller
{
    use CalculatesScores, ArchetypeFinder;

    public function index()
    {
        $userId = auth()->id();
        $existingResult = Result::where('user_id', $userId)->first();

        if ($existingResult) {
            return to_route('dashboard');
        }

        $hollandCodeSets = Session::get('holland_code_sets', $this->initializeHollandCodeSets());
        $basicInterests = Session::get('basic_interests', $this->initializeBasicInterests());
        $currentSetIndex = Session::get('current_set_index', 0);
        $currentItemIndex = Session::get('current_item_index', 0);
        $responses = Session::get('test_responses', []);
        $testStage = Session::get('test_stage', 'holland_codes');

        if ($testStage === 'holland_codes' && $currentSetIndex >= count($hollandCodeSets)) {
            $testStage = 'basic_interests';
            $currentSetIndex = 0;
            $currentItemIndex = 0;
            Session::put('test_stage', $testStage);
            Session::put('current_set_index', $currentSetIndex);
            Session::put('current_item_index', $currentItemIndex);
        }

        if ($testStage === 'basic_interests' && $currentItemIndex >= count($basicInterests)) {
            return $this->processResults($responses);
        }

        $currentItem = $testStage === 'holland_codes'
            ? $hollandCodeSets[$currentSetIndex]['items'][$currentItemIndex]
            : $basicInterests[$currentItemIndex];

        return Inertia::render('Test/MainTest', [
            'hollandCodeData' => $hollandCodeSets,
            'basicInterests' => $basicInterests,
            'currentSetIndex' => $currentSetIndex,
            'currentItemIndex' => $currentItemIndex,
            'currentItem' => $currentItem,
            'totalSets' => $testStage === 'holland_codes' ? count($hollandCodeSets) : 1,
            'totalItems' => $testStage === 'holland_codes' ? count($hollandCodeSets[$currentSetIndex]['items']) : count($basicInterests),
            'responses' => $responses,
            'testStage' => $testStage,
        ]);
    }

    public function storeHollandCodeResponse(StoreResponseRequest $request)
    {
        $validated = $request->validated();
        $responses = Session::get('holland_code_responses', []);
        $responses[] = $validated;
        Session::put('holland_code_responses', $responses);

        $hollandCodeSets = Session::get('holland_code_sets');
        $currentSetIndex = Session::get('current_set_index', 0);
        $currentItemIndex = Session::get('current_item_index', 0);

        $currentItemIndex++;
        if ($currentItemIndex >= count($hollandCodeSets[$currentSetIndex]['items'])) {
            $currentSetIndex++;
            $currentItemIndex = 0;
        }

        if ($currentSetIndex >= count($hollandCodeSets)) {
            Session::put('test_stage', 'basic_interests');
            return $this->startBasicInterests();
        }
        $traitScores = $this->calculateHollandScores($responses);
        ds($traitScores);

        Session::put('current_set_index', $currentSetIndex);
        Session::put('current_item_index', $currentItemIndex);

        $currentItem = $hollandCodeSets[$currentSetIndex]['items'][$currentItemIndex];
        return to_route('main-test');
    }

    public function storeBasicInterestResponse(StoreResponseRequest $request)
    {
        $validated = $request->validated();
        $responses = Session::get('basic_interest_responses', []);
        $responses[] = $validated;

        Session::put('basic_interest_responses', $responses);

        $basicInterests = Session::get('basic_interests');
        $currentItemIndex = Session::get('current_item_index', 0);

        $currentItemIndex++;

        if ($currentItemIndex >= count($basicInterests)) {
            return $this->processResults();
        }

        Session::put('current_item_index', $currentItemIndex);

        $currentItem = $basicInterests[$currentItemIndex];

        $categoryScores = [];
        foreach ($responses as $response) {
            $category = $response['category'];
            $answer = $response['answer'];

            if (!isset($categoryScores[$category])) {
                $categoryScores[$category] = 0;
            }

            $categoryScores[$category] += $answer;
        }

        arsort($categoryScores);
        $topCategories = array_slice($categoryScores, 0, 3, true);

        $result = [];
        foreach ($topCategories as $category => $score) {
            $categoryId = BasicInterest::where('category', $category)->value('id');
            $result[] = $categoryId;
        }

        ds($result);

        return to_route('main-test');

    }

    private function initializeHollandCodeSets()
    {
        $hollandCodeSets = HollandCodeSet::with('items')->get()->map(function ($set) {
            return [
                'id' => $set->id,
                'title' => $set->title,
                'items' => $set->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'text' => $item->text,
                        'type' => $item->type,
                    ];
                })->toArray(),
            ];
        })->toArray();

        Session::put('holland_code_sets', $hollandCodeSets);

        return $hollandCodeSets;
    }

    private function initializeBasicInterests()
    {
        $basicInterests = BasicInterest::all()->map(function ($interest) {
            return [
                'id' => $interest->id,
                'category' => $interest->category,
                'question' => $interest->question,
            ];
        })->toArray();

        Session::put('basic_interests', $basicInterests);

        return $basicInterests;
    }

    private function startBasicInterests()
    {
        $basicInterests = Session::get('basic_interests');


        Session::put('current_item_index', 0);
        $currentItem = $basicInterests[0];

        return to_route('main-test');

    }

    private function processResults()
    {
        $hollandCodeResponses = Session::get('holland_code_responses', []);
        $basicInterestResponses = Session::get('basic_interest_responses', []);

        $hollandScores = $this->calculateHollandScores($hollandCodeResponses);
        $archetype =  $this->getArchetypeAndTopScores($hollandScores);
        ds(['archetype'=>$archetype]);

        $categoryScores = [];
        foreach ($basicInterestResponses as $response) {
            $category = $response['category'];
            $answer = $response['answer'];

            if (!isset($categoryScores[$category])) {
                $categoryScores[$category] = 0;
            }

            $categoryScores[$category] += $answer;
        }

        arsort($categoryScores);
        $topCategories = array_slice($categoryScores, 0, 3, true);

        $basic_interest_ids = [];
        foreach ($topCategories as $category => $score) {
            $categoryId = BasicInterest::where('category', $category)->value('id');
            $basic_interest_ids[] = $categoryId;
        }

        $scriptPath = app_path('/python/test.py');

        $process = new Process([
            'python3',
            $scriptPath,
            json_encode($basic_interest_ids),
            json_encode($hollandScores)
        ]);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error('Python script execution failed', [
                'error' => $process->getErrorOutput(),
                'command' => $process->getCommandLine(),
                'working_directory' => $process->getWorkingDirectory(),
            ]);
            throw new ProcessFailedException($process);
        }

        $output = $process->getOutput();

        Log::info('Python script output', ['output' => $output]);

        $decodedOutput = json_decode($output, true);

        try {
            Result::create([
                'user_id' => auth()->id(),
                'scores' => json_encode($hollandScores, JSON_THROW_ON_ERROR),
                'jobs' => json_encode($decodedOutput, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                'highestTwoScores' => $archetype['topTraits'],
                'Archetype' => $archetype['archetypes'],
            ]);
        } catch (\JsonException $e) {
            Log::error('Failed to encode scores or jobs for database storage', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Failed to save assessment results');
        }

        return to_route('results');
        Session::forget(['holland_code_sets', 'basic_interests', 'current_set_index', 'current_item_index', 'holland_code_responses', 'basic_interest_responses', 'test_stage']);



    }
}
