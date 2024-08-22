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

        $inputValues = array_values($scores);

        $data = $jobMatcherService->loadData($filePath);
        $closestJobs = $jobMatcherService->findClosestJobs($data, $inputValues, 9);


        $userChosenZone = 3;
        $top3JobsInZone = $jobMatcherService->filterJobsByZone($data, $closestJobs, $userChosenZone);

        return    $closestJobs;

    }
}
