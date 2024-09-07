<?php
namespace App\Http\Controllers;

use App\Models\JobInfo;
use App\Models\Question;
use App\Models\Result;
use App\Models\Score;
use App\Services\JobMatcherService;
use App\Traits\ArchetypeFinder;
use App\Traits\CalculatesScores;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ActivityProgressController extends Controller
{
    use CalculatesScores,ArchetypeFinder;

 private const BIG_FIVE_CATEGORIES = ['Extraversion', 'Agreeableness', 'Conscientiousness', 'Neuroticism', 'Openness'];
private const HOLLAND_CODE_CATEGORIES = ['Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional'];

public function index()
{
//    $lastTest = Auth::user()->results()->latest('created_at')->first();
//    $lastTestDate = $lastTest ? Carbon::parse($lastTest->created_at) : null;
//    $isOver10Days = !$lastTestDate || $lastTestDate->lt(Carbon::now()->subDays(10));
//
//    if (!$isOver10Days) {
//        return to_route('results');
//    }

    $activities = $this->initializeActivities();
    ds($activities);
    return Inertia::render('ActivityProgress', [
        'activities' => $activities,
        'initialResponses' => [],
        'initialIndex' => 0,
    ]);
}

private function initializeActivities(): array
{
    $bigFiveActivities = $this->fetchActivitiesByType('big_five', self::BIG_FIVE_CATEGORIES);
    $hollandCodeActivities = $this->fetchActivitiesByType('holland_codes', self::HOLLAND_CODE_CATEGORIES);

    return array_merge($bigFiveActivities, $hollandCodeActivities);
}

private function fetchActivitiesByType(string $type, array $categories): array
{
    $activities = Question::whereIn('trait_category', $categories)
        ->where('type', $type)
        ->orderBy(DB::raw("FIELD(trait_category, '" . implode("', '", $categories) . "')"))
        ->limit(12)
        ->get();

    return $activities->map(function ($activity) {
        return [
            'category' => $activity->trait_category,
            'id' => $activity->id,
            'type' => $activity->type,
            'name' => $activity->question_text
        ];
    })->toArray();
}
 public function submit(Request $request, JobMatcherService $jobMatcherService): \Illuminate\Http\RedirectResponse
{
    $responses = $request->input('responses', []);

    if (empty($responses)) {
        // Handle empty responses
        return to_route('results');
    }

    $activities = $this->getActivities($responses);
    if ($activities->isEmpty()) {
        // Handle empty activities
        return to_route('results');
    }

    $formattedResponses = $this->formatResponses($activities, $responses);
    $scores = $this->calculateScores($formattedResponses);

    $this->logResponses($scores, $formattedResponses);

    $targetHolland = $this->formatHollandScores($scores);
    $targetBigFive = $this->formatBigFiveScores($scores);
    $archetype = $this->getArchetypeAndTopScores($targetHolland);

    $closestJobs = $this->matchJobs($targetHolland, $targetBigFive);
      ds($closestJobs);
      ds($archetype);
    $result = $this->saveResult($closestJobs, $scores, $archetype);

    return to_route('results')->with('closestJobs', $closestJobs);
}

private function getActivities($responses)
{
    $activityIds = array_keys($responses);
    return Question::whereIn('id', $activityIds)->get();
}

private function formatResponses($activities, $responses)
{
    $formattedResponses = [
        'big_five' => [],
        'holland_codes' => []
    ];

    $activities->each(function ($activity) use ($responses, &$formattedResponses) {
        if ($activity->type === 'big_five') {
            $category = $activity->trait_category;
            if (in_array($category, self::BIG_FIVE_CATEGORIES)) {
                $formattedResponses['big_five'][$category][] = $responses[$activity->id];
            }
        } elseif ($activity->type === 'holland_codes') {
            $category = $activity->trait_category;
            if (in_array($category, self::HOLLAND_CODE_CATEGORIES)) {
                $formattedResponses['holland_codes'][$category][] = $responses[$activity->id];
            }
        }
    });

    return $formattedResponses;
}

private function formatHollandScores($scores)
{
    return [
        'Realistic' => $scores['Realistic'] ?? 0,
        'Investigative' => $scores['Investigative'] ?? 0,
        'Artistic' => $scores['Artistic'] ?? 0,
        'Social' => $scores['Social'] ?? 0,
        'Enterprising' => $scores['Enterprising'] ?? 0,
        'Conventional' => $scores['Conventional'] ?? 0,
    ];
}

private function formatBigFiveScores($scores)
{
    return [
        'Openness' => $scores['Openness'] ?? 0,
        'Conscientiousness' => $scores['Conscientiousness'] ?? 0,
        'Extraversion' => $scores['Extraversion'] ?? 0,
        'Agreeableness' => $scores['Agreeableness'] ?? 0,
        'Neuroticism' => $scores['Neuroticism'] ?? 0,
    ];
}

private function logResponses($scores, $formattedResponses)
{
    Log::info('Formatted Responses: ', ['formatted_responses' => $formattedResponses]);
}

private function matchJobs($targetHolland, $targetBigFive)
{
    $scriptPath = app_path('/python/test.py');
    $process = new Process(['python3', $scriptPath, json_encode($targetHolland), json_encode($targetBigFive)]);
    $process->run();

    if (!$process->isSuccessful()) {
        Log::error('Python script execution failed', ['error' => $process->getErrorOutput()]);
        throw new ProcessFailedException($process);
    }

    $output = $process->getOutput();
    try {
        return json_decode($output, true, 512, JSON_THROW_ON_ERROR);
    } catch (\JsonException $e) {
        Log::error('Failed to decode JSON from Python script', ['error' => $e->getMessage(), 'output' => $output]);
        throw new \RuntimeException('Failed to process job matching results');
    }
}

private function saveResult($closestJobs, $scores, $archetype)
{
    try {
        return Result::create([
            'user_id' => auth()->id(),
            'scores' => json_encode($scores, JSON_THROW_ON_ERROR),
            'jobs' => json_encode($closestJobs, JSON_THROW_ON_ERROR),
            'highestTwoScores' =>$archetype['topTraits'],
            'Archetype' => $archetype['archetypes'],
        ]);
    } catch (\JsonException $e) {
        Log::error('Failed to encode scores or jobs for database storage', ['error' => $e->getMessage()]);
        throw new \RuntimeException('Failed to save assessment results');
    }
}


}
