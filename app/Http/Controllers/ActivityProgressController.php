<?php
namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Score;
use App\Services\JobMatcherService;
use App\Traits\CalculatesScores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ActivityProgressController extends Controller
{
    use CalculatesScores;

    private const CATEGORIES = ['Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional'];
    private const INTEREST_TYPES = ['Basic', 'General'];

    public function index(): \Inertia\Response
    {
        $activities = $this->initializeActivities();
        return Inertia::render('ActivityProgress', [
            'activities' => $activities,
            'initialResponses' => [],
            'initialIndex' => 0,
        ]);
    }

    private function initializeActivities(): array
    {
        $allActivities = $this->fetchAllActivities();
        $groupedActivities = $this->groupActivities($allActivities);
        return $this->formatActivities($groupedActivities);
    }

    private function fetchAllActivities()
    {
        return Activity::whereIn('category', self::CATEGORIES)
            ->whereIn('interest_type', self::INTEREST_TYPES)
            ->orderBy(DB::raw("FIELD(category, '" . implode("', '", self::CATEGORIES) . "')"))
            ->orderBy(DB::raw("FIELD(interest_type, '" . implode("', '", self::INTEREST_TYPES) . "')"))
            ->get()
            ->groupBy('category');
    }

    private function groupActivities($allActivities): array
    {
        $groupedActivities = [];
        foreach (self::CATEGORIES as $category) {
            $groupedActivities[$category] = isset($allActivities[$category])
                ? $allActivities[$category]->whereIn('interest_type', self::INTEREST_TYPES)->take(5)
                : collect();
        }
        return $groupedActivities;
    }

    private function formatActivities($groupedActivities): array
    {
        return collect($groupedActivities)->flatMap(function ($activities, $category) {
            return array_map(fn ($activity) => [
                'category' => $category,
                'id' => $activity['id'],
                'name' => $activity['activity']
            ], $activities->toArray());
        })->toArray();
    }

    public function submit(Request $request, JobMatcherService $jobMatcherService): \Illuminate\Http\RedirectResponse
    {
        $responses = $request->input('responses', []);
        Log::info('Responses received: ', ['responses' => $responses]);

        $activities = Activity::all();
        $scores = $this->calculateScore($activities, $responses);
        $userId = auth()->id();

        $closestJobs = app(JobMatcherController::class)->matchJobsWithScores($scores, $jobMatcherService);

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
ds($scores);
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
