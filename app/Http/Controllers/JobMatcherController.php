<?php

namespace App\Http\Controllers;

use App\Services\JobMatcherService;

class JobMatcherController extends Controller
{
    protected JobMatcherService $jobMatcherService;

    public function __construct(JobMatcherService $jobMatcherService)
    {
        $this->jobMatcherService = $jobMatcherService;
    }


    public function matchJobsWithScores(array $scores, JobMatcherService $jobMatcherService): array
    {
        $filePath = public_path('updated_interests_with_job_zone.xlsx');

        // Assuming scores are now array values in order
        $inputValues = array_values($scores);

        $data = $jobMatcherService->loadData($filePath);
        $closestJobs = $jobMatcherService->findClosestJobs($data, $inputValues, 5);

        $userChosenZone = 3;
        $top3JobsInZone = $jobMatcherService->filterJobsByZone($data, $closestJobs, $userChosenZone);

        return [
            'closest_jobs' => $closestJobs,
            'top_3_jobs_in_zone' => $top3JobsInZone
        ];
    }
}
