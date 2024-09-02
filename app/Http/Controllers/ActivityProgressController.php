<?php
namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Score;
use App\Services\JobMatcherService;
use App\Traits\CalculatesScores;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ActivityProgressController extends Controller
{
    use CalculatesScores;

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
ds( $this->formatActivities($allActivities, $categories));
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

        $allActivities = $this->initializeActivities();
        $scores = $this->calculateScore($allActivities, $responses);
        $userId = auth()->id();

        $closestJobs = app(JobMatcherController::class)->matchJobs($scores, $jobMatcherService);

        Score::create([
            'user_id' => $userId,
            'scores' => json_encode($scores),
            'jobs' => json_encode($closestJobs)
        ]);

        return to_route('results');
    }

    public function results(): \Inertia\Response
    {
        $userId = auth()->id();
        $score = Score::where('user_id', $userId)->latest()->first();

        if ($score) {
            $scores = json_decode($score->scores, true, 512, JSON_THROW_ON_ERROR);
            $closestJobs = json_decode($score->jobs, true);

            Log::debug($closestJobs);

            return Inertia::render('Results', [
                'scores' => $scores,
                'jobs' => $closestJobs,
            ]);
        }

        return Inertia::render('Results', [
            'scores' => [],
            'jobs' => [],
        ]);
    }
}
