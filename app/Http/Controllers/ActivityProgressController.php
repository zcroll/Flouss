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
        $lastTest = Auth::user()->test()->latest('created_at')->first();
        $lastTestDate = $lastTest ? Carbon::parse($lastTest->created_at) : null;
        $isOver10Days = !$lastTestDate || $lastTestDate->lt(Carbon::now()->subDays(10));

        if (!$isOver10Days) {
            return to_route('results');
        }

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
        $allActivities = Question::whereIn('trait_category', $categories)
            ->where('type', $type)
            ->orderBy(DB::raw("FIELD(trait_category, '" . implode("', '", $categories) . "')"))
            ->get()
            ->groupBy('trait_category');
//ds( $this->formatActivities($allActivities, $categories));
        return $this->formatActivities($allActivities, $categories);
    }

    private function formatActivities($groupedActivities, $categories): array
    {
        $formattedActivities = [];

        foreach ($categories as $category) {
            if (isset($groupedActivities[$category])) {
                $formattedActivities[] = array_map(fn($activity) => [
                    'category' => $category,
                    'id' => $activity['id'],
                    'type' => $activity['type'],
                    'name' => $activity['question_text']
                ], $groupedActivities[$category]->toArray());
            }
        }

        return array_merge(...$formattedActivities);
    }

    public function submit(Request $request, JobMatcherService $jobMatcherService): \Illuminate\Http\RedirectResponse
    {
        $responses = $request->input('responses', []);
        $formattedResponses = [
            'big_five' => [],
            'holland_codes' => []
        ];
        foreach ($responses as $activityId => $response) {
            $activity = Question::find($activityId);
            if ($activity) {
                if ($activity->type === 'big_five') {
                    $category = $activity->trait_category;
                    if (!in_array($category, self::BIG_FIVE_CATEGORIES)) continue;
                    $formattedResponses['big_five'][$category][] = $response;
                } elseif ($activity->type === 'holland_codes') {
                    $category = $activity->trait_category;
                    if (!in_array($category, self::HOLLAND_CODE_CATEGORIES)) continue;
                    $formattedResponses['holland_codes'][$category][] = $response;
                }
            }
        }

        // Log formatted responses
        Log::info('Formatted Responses: ', ['formatted_responses' => $formattedResponses]);

        // Calculate scores
        $scores = $this->calculateScores($formattedResponses);

        // Separate scores into $targetHolland and $targetBigFive
        $targetHolland = [
            'Realistic' => $scores['Realistic'] ?? 0,
            'Investigative' => $scores['Investigative'] ?? 0,
            'Artistic' => $scores['Artistic'] ?? 0,
            'Social' => $scores['Social'] ?? 0,
            'Enterprising' => $scores['Enterprising'] ?? 0,
            'Conventional' => $scores['Conventional'] ?? 0,
        ];

        $targetBigFive = [
            'Openness' => $scores['Openness'] ?? 0,
            'Conscientiousness' => $scores['Conscientiousness'] ?? 0,
            'Extraversion' => $scores['Extraversion'] ?? 0,
            'Agreeableness' => $scores['Agreeableness'] ?? 0,
            'Neuroticism' => $scores['Neuroticism'] ?? 0,
        ];
        $theArchetype = $this->getArchetypeAndTopScores($targetHolland);
        $archetypes = $theArchetype['archetypes'];
        $topTraits = $theArchetype['topTraits'];
//        ds($theArchetype->archetypes);
        // Match jobs
        $scriptPath = app_path('/python/test.py');
        $process = new Process(['python3', $scriptPath, json_encode($targetHolland), json_encode($targetBigFive)]);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error('Python script execution failed', ['error' => $process->getErrorOutput()]);
            throw new ProcessFailedException($process);
        }

        $output = $process->getOutput();



        try {
            ds($scores);
            $closestJobs = json_decode($output, true, 512, JSON_THROW_ON_ERROR);

            // Log the decoded closest jobs
            Log::info('Closest matching jobs:', ['jobs' => $closestJobs]);
        } catch (\JsonException $e) {
            Log::error('Failed to decode JSON from Python script', ['error' => $e->getMessage(), 'output' => $output]);
            throw new \RuntimeException('Failed to process job matching results');
        }

        $userId = auth()->id();

        try {
            Result::create([
                'user_id' => $userId,
                'scores' => json_encode($scores, JSON_THROW_ON_ERROR),
                'jobs' => json_encode($closestJobs, JSON_THROW_ON_ERROR),
                'highestTwoScores' =>$topTraits,
                'Archetype' => $archetypes,
            ]);
        } catch (\JsonException $e) {
            Log::error('Failed to encode scores or jobs for database storage', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Failed to save assessment results');
        }

        return to_route('results')->with('closestJobs', $closestJobs);
    }

    public function results(): \Inertia\Response
    {
        $userId = auth()->id();
        $score = Result::where('user_id', $userId)->latest()->first();

        if ($score) {
            // Decode JSON fields only if they are strings
            $scores = is_string($score->scores) ? json_decode($score->scores, true, 512, JSON_THROW_ON_ERROR) : $score->scores;
            $topTraits = is_string($score->highestTwoScores) ? json_decode($score->highestTwoScores, true, 512, JSON_THROW_ON_ERROR) : $score->highestTwoScores;
            $archetypes = is_string($score->Archetype) ? json_decode($score->Archetype, true, 512, JSON_THROW_ON_ERROR) : $score->Archetype;
            $closestJobs = is_string($score->jobs) ? json_decode($score->jobs, true) : $score->jobs;

            // Extract job IDs
            $jobIds = array_map(fn($job) => $job['job_info_id'], $closestJobs);

            // Fetch job details
            $jobs = JobInfo::whereIn('id', $jobIds)
                ->select('id', 'name', 'slug', 'image')
                ->get();
            $archetype = is_array($archetypes) ? $archetypes[0] : $archetypes;
            // Pass data to the Inertia view
            return Inertia::render('Results', [
                'scores' => $scores,
                'jobs' => $jobs->toArray(),
                'highestTwoScores' => $topTraits,
                'Archetype' => $archetypes,
            ]);
        }

        // Return default values if no score is found
        return Inertia::render('Results', [
            'scores' => [],
            'jobs' => [],
            'highestTwoScores' => [],
            'Archetype' => [],
        ]);
    }
}
