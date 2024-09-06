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
public function results(): \Inertia\Response
{
    $userId = auth()->id();
    $scores = ResultResource::collection(Result::with('user')->where('user_id', $userId)->get());
    //Now you have a collection, you can manipulate it
    $firstScore = $scores->first();
    ds(       $firstScore->user->uuid);
//    ds($firstScore->scores);

    $topTraits = $firstScore->highestTwoScores;
    ds($topTraits);
    $Archetype = $firstScore->Archetype;
    $closestJobs = collect($firstScore->jobs);

    $jobIds = $closestJobs->pluck('job_info_id');$jobs = JobInfo::whereIn('id', $jobIds)
        ->select('id', 'name', 'slug', 'image')
        ->get();
    $Archetype = DB::table('persona')->where('name', '=', "Mentor")->first();

    return Inertia::render('Result/Results', [
        'userId' => $firstScore->user->uuid,
        'scores' => $firstScore->scores,
        'jobs' => $jobs,
        'highestTwoScores' => $topTraits,
        'Archetype' => $Archetype,
    ]);
}
    public  function personality($id): \Inertia\Response
    {
        $userId = auth()->id();


        $scores = ResultResource::collection(Result::with('user')->where('user_id', $userId)->get());

        $firstScore = $scores->first();
        $Archetype = $firstScore->Archetype;
        $ArchetypeData = DB::table('persona')->where('name', '=', "Mentor")->first();
        $insights = DB::table('insights')
            ->join('insight_categories', 'insights.insight_category_slug', '=', 'insight_categories.insight_category_slug')
            ->where('insights.persona_id', '=', $ArchetypeData->id)
            ->select('insights.*', 'insight_categories.insight_category')
            ->get();

        ds($insights);

      return  Inertia::render('Result/Personality', [
          "ArchetypeData"=>$ArchetypeData
      ]);







    }
}
