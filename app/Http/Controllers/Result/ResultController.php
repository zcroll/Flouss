<?php

namespace App\Http\Controllers\Result;

use App\Http\Controllers\Controller;
use App\Models\JobInfo;
use App\Models\Result;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ResultController extends Controller
{
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
            $Archetype = DB::table('persona')->where('name', '=', "t")->first();
            ds([' the archetype details' => $Archetype,]);

            return Inertia::render('Results', [
                'scores' => $scores,
                'jobs' => $jobs->toArray(),
                'highestTwoScores' => $topTraits,
                'Archetype' => $Archetype,
            ]);
        }

        return Inertia::render('Results', [
            'scores' => [],
            'jobs' => [],
            'highestTwoScores' => [],
            'Archetype' => [],
        ]);
    }
}
