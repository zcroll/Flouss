<?php

namespace App\Http\Controllers\Result;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResultResource;
use App\Http\Resources\UserResource;
use App\Models\JobInfo;
use App\Models\Result;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ResultController extends Controller
{
    /**
     * @throws \JsonException
     */
    public function results() : \Inertia\Response|\Illuminate\Http\RedirectResponse
    {
        $userId = auth()->id();
        $scores = ResultResource::collection(Result::with('user')->where('user_id', $userId)->latest()->get());

        if ($scores->isEmpty()) {
            return to_route('dashboard');
        }

        $firstScore = $scores->first();

        $Archetype = $firstScore->Archetype;
        $closestJobs = collect($firstScore->jobs);
        $decodedJobs = json_decode($closestJobs[0], true, 512, JSON_THROW_ON_ERROR);

        $jobsCollection = collect($decodedJobs);

        $jobIds = $jobsCollection->pluck('job_info_id');
        $jobIdsArray = $jobIds->toArray();
        $jobs = JobInfo::whereIn('id', $jobIds)
            ->select('id', 'name', 'slug', 'image')
            ->get();

        $Archetype = DB::table('persona')->where('name', '=', "Mentor")->first();

        if ($firstScore->user) {
            return Inertia::render('Result/Results', [
                'userId' => $firstScore->user->uuid,
                'scores' => $firstScore->scores,
                'jobs' => $jobs,
                'Archetype' => $Archetype,
            ]);
        }

        return to_route('dashboard');
    }

    public function personality($id): \Inertia\Response
    {
        $userId = auth()->id();

        $scores = ResultResource::collection(Result::with('user')->where('user_id', $userId)->get());

        if ($scores->isEmpty()) {
            return to_route('dashboard');
        }

        $firstScore = $scores->first();
        ds($firstScore->scores);
        $Archetype = $firstScore->Archetype;
        $ArchetypeData = DB::table('persona')->where('name', '=', "Mentor")->first();
        $insights = DB::table('insights')
            ->join('insight_categories', 'insights.insight_category_slug', '=', 'insight_categories.insight_category_slug')
            ->where('insights.persona_id', '=', $ArchetypeData->id)
            ->select('insights.*', 'insight_categories.insight_category')
            ->distinct()
            ->get();

        ds($insights);

        return Inertia::render('Result/Personality', [
            "ArchetypeData" => $ArchetypeData,
            'firstScore' => $firstScore->scores,
            'Insights' => $insights,
        ]);
    }
}
