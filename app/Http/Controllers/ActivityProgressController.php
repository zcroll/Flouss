<?php

namespace App\Http\Controllers;

use App\Models\Activity;
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
        $allActivities = $this->fetchAllActivitiesByCategoryAndType();
        $groupedActivities = $this->groupActivitiesByCategory($allActivities);

        return $this->formatActivities($groupedActivities);
    }

    private function fetchAllActivitiesByCategoryAndType()
    {
        return Activity::whereIn('category', ['Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional'])
            ->whereIn('interest_type', ['Basic', 'General'])
            ->orderBy(DB::raw("FIELD(category, 'Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional')"))
            ->orderBy(DB::raw("FIELD(interest_type, 'Basic', 'General')"))
            ->get()
            ->groupBy('category');
    }

    private function groupActivitiesByCategory($allActivities): array
    {
        $groupedActivities = [];

        foreach (['Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional'] as $category) {
            if (isset($allActivities[$category])) {
                $basicActivities = $allActivities[$category]->where('interest_type', 'Basic')->take(5);
                $generalActivities = $allActivities[$category]->where('interest_type', 'General')->take(5);
                $groupedActivities[$category] = $basicActivities->merge($generalActivities);
            } else {
                $groupedActivities[$category] = collect();
            }
        }

        return $groupedActivities;
    }

    private function formatActivities($groupedActivities)
    {
        return collect($groupedActivities)->flatMap(function ($activities, $category) {
            return array_map(function ($activity) use ($category) {
                return [
                    'category' => $category,
                    'id' => $activity['id'],
                    'name' => $activity['activity']
                ];
            }, $activities->toArray());
        })->toArray();
    }

    public function submit(Request $request, JobMatcherService $jobMatcherService)
    {
        $responses = $request->input('responses',[]);
        Log::info('Responses received: ', ['responses' => $responses]);
        Session::put('responses', $responses);
        return to_route('results');
    }

    public function results(JobMatcherService $jobMatcherService): \Inertia\Response
    {
        $responses = Session::get('responses',[]);
//        if (empty($responses)) {
//            return redirect()->route('activities');
//        }
        $activities = Activity::all();
        $scores = $this->calculateScore($activities, $responses);
        $closestJobs = app(JobMatcherController::class)->matchJobsWithScores($scores, $jobMatcherService);
        Log::debug($closestJobs);


        return Inertia::render('Results', [
            'scores' => $scores,
            'jobs' => $closestJobs,
        ]);
    }
}
