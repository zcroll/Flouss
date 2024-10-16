<?php

namespace App\Http\Controllers\Result;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResultResource;
use App\Models\JobInfo;
use App\Models\Result;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\ArchetypeCareer;
use App\Models\ArchetypeCareerJobMatch;
use App\Models\Insight;
use App\Models\Persona;

class ResultController extends Controller
{
    /**
     * @throws \JsonException
     */
    public function results() : \Inertia\Response|\Illuminate\Http\RedirectResponse
    {
        $locale = app()->getLocale();
        $nameColumn = $locale === 'fr' ? 'name_fr' : 'name';

        $userId = Auth::id();
        $scores = ResultResource::collection(Result::with('user')->where('user_id', $userId)->latest()->get());

        if ($scores->isEmpty()) {
            return to_route('dashboard');
        }

        $firstScore = $scores->first();

        $Archetype = $firstScore->Archetype;
        $closestJobs = collect($firstScore->jobs);
        $decodedJobs = json_decode($closestJobs[0], true, 512, JSON_THROW_ON_ERROR);

        $jobsCollection = collect($decodedJobs);
        $jobIds = $jobsCollection->pluck('id');
        $jobIdsArray = $jobIds->toArray();
        $jobs = JobInfo::whereIn('id', $jobIds)
            ->select('id', $nameColumn . ' as name', 'slug', 'image')
            ->get();

            ds($jobs->toArray());

        // Merge job information with distances and calculate ratings
        // $jobsWithDistances = $jobs->map(function ($job) use ($jobsCollection) {
        //     $jobData = $jobsCollection->firstWhere('job_info', $job->id);
        //     $distance = $jobData['distance'];
        //     $rating = 5 - min(round($distance * 60), 1.5);

        //     return [
        //         'id' => $job->id,
        //         'name' => $job->name,
        //         'slug' => $job->slug,
        //         'image' => $job->image,
        //         'distance' => $distance,
        //         'rating' => $rating
        //     ];
        // })->sortBy('distance')->values();

        $Archetype = DB::table('persona')->where('name', '=', "Mentor")->first();

        $archetypeDiscovery = DB::table('archetype_discoveries')->where('slug', '=', "Mentor")->first();

        ds(['archetypeDiscovery'=>$archetypeDiscovery]);
        ds($firstScore->scores,);
        // Get the scores from $firstScore
        $scores = json_decode($firstScore->scores, true);

        if (is_array($scores)) {
            // Sort the scores in descending order
            arsort($scores);

            // Get the top two scores
            $topTwoScores = array_slice($scores, 0, 2, true);

            // Create an array with the top two traits and their scores
            $topTwoResults = [];
            foreach ($topTwoScores as $trait => $score) {
                $topTwoResults[$trait] = round($score * 100); // Convert to percentage and round
            }
        } else {
            // Handle the case where $scores is not an array
            Log::error('Scores is not an array', ['scores' => $firstScore->scores]);
            $topTwoResults = [];
        }
        // ds($topTwoResults);

        // Get the careers based on the archetype using the ArchetypeCareer model
        ds($Archetype);
        $careerColumn = $locale === 'fr' ? 'name_fr as career' : 'career as career';
        $archetypeCareers = ArchetypeCareer::where('archetype', 'Mentor')
            ->get([$careerColumn, 'image', 'slug']);

        $similarJobs = ArchetypeCareerJobMatch::where('archetype', 'Mentor')
            ->where('similarity_score', '>', 0.7)
            ->orderBy('similarity_score', 'desc')
            ->groupBy('job_id', 'job_name', 'similarity_score', 'career')
            ->select('job_id', 'job_name', 'similarity_score', 'career')
            ->get();

        $combinedJobs = $archetypeCareers->map(function ($career) use ($similarJobs) {
            $matchingJobs = $similarJobs->where('career', $career->career);
            return [
                'career' => $career->career,
                'image' => $career->image,
                'slug' => $career->slug,
                'similar_jobs' => $matchingJobs->map(function ($job) {
                    return [
                        'job_id' => $job->job_id,
                        'job_name' => $job->job_name,
                        'similarity_score' => $job->similarity_score,
                    ];
                })->values()->all(),
            ];
        });





        if ($firstScore) {
            return Inertia::render('Result/Results', [
                'userId' => $firstScore->uuid,
                'scores' => $topTwoResults,
                'jobs' => $jobs,
                'Archetype' => $Archetype,
                'archetypeDiscovery' => $archetypeDiscovery,
                'ArchetypeJobs' => $archetypeCareers,
                'SimilarJobs' => $similarJobs,
                'combinedJobs' => $combinedJobs,
            ]);
        }

        return to_route('dashboard');
    }

    public function personality($id): \Inertia\Response
    {
        $locale = app()->getLocale();
        $nameColumn = $locale === 'fr' ? 'name_fr' : 'name';
        $insightColumn = $locale === 'fr' ? 'insight_fr' : 'insight';

        $userId = Auth::id();

        $scores = ResultResource::collection(Result::with('user')->where('user_id', $userId)->get());

        if ($scores->isEmpty()) {
            return to_route('dashboard');
        }

        $firstScore = $scores->first();
        ds($firstScore->scores);
        $Archetype = $firstScore->Archetype;
        $ArchetypeData = DB::table('persona')->where('name', '=', "mentor")->first();

        // Fetch insights grouped by category
        $insights = Insight::where('persona_id', $ArchetypeData->id)
            ->select('insight_category_slug', $insightColumn)
            ->get()
            ->groupBy('insight_category_slug')
            ->map(function ($group) use ($insightColumn) {
                return $group->pluck($insightColumn);
            });



        // Transform the grouped insights into the desired format


        $archetypeDiscovery = DB::table('archetype_discoveries')->where('slug', '=', "anchor")->first();
        // Update the query to use the new model
           ds(['archetypeDiscovery'=>$archetypeDiscovery]);
           ds(['ArchetypeData'=>$ArchetypeData]);
           ds(['insights'=>$insights->toArray()]);
        return Inertia::render('Result/StrategistDescription', [
            "ArchetypeData" => $ArchetypeData,
            'firstScore' => $firstScore->scores,
            'Insights' => $insights,
            'archetypeDiscovery' => $archetypeDiscovery,
        ]);
    }
}
