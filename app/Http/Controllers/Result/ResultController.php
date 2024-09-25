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
use App\Models\Insight;
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
        $jobIds = $jobsCollection->pluck('job_info_id');
        $jobIdsArray = $jobIds->toArray();
        $jobs = JobInfo::whereIn('id', $jobIds)
            ->select('id', $nameColumn . ' as name', 'slug', 'image')
            ->get();

        // Merge job information with distances and calculate ratings
        $jobsWithDistances = $jobs->map(function ($job) use ($jobsCollection) {
            $jobData = $jobsCollection->firstWhere('job_info_id', $job->id);
            $distance = $jobData['distance'];
            $rating = 5 - min(round($distance * 60), 1.5); 

            return [
                'id' => $job->id,
                'name' => $job->name,
                'slug' => $job->slug,
                'image' => $job->image,
                'distance' => $distance,
                'rating' => $rating
            ];
        })->sortBy('distance')->values();

        $Archetype = DB::table('persona')->where('name', '=', "Inventor")->first();
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
        $archetypeCareers = ArchetypeCareer::where('archetype', $Archetype->name)
            ->get([$careerColumn, 'image', 'slug']);
           
        ds($archetypeCareers->toArray());
        // Add the top two results to the Inertia response
            ds($topTwoResults   );
        if ($firstScore) {
            return Inertia::render('Result/Results', [
                'userId' => $firstScore->uuid,
                'scores' => $topTwoResults,
                'jobs' => $jobsWithDistances,
                'Archetype' => $Archetype,
                'ArchetypeJobs' => $archetypeCareers,
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
        $ArchetypeData = DB::table('persona')->where('name', '=', "Mentor")->first();
        
        $insights = Insight::with('persona')
            ->join('insight_categories', 'insights.insight_category_slug', '=', 'insight_categories.insight_category_slug')
            ->where('insights.persona_id', '=', $ArchetypeData->id)
            ->select(
                'insights.id', 
                'insights.persona_id', 
                'insights.insight_category_slug', 
                'insight_categories.insight_category'
            )
            ->when($locale === 'fr', function ($query) {
                return $query->selectRaw('CASE WHEN insights.insight_fr IS NOT NULL AND insights.insight_fr != "" THEN insights.insight_fr ELSE insights.insight END as insight');
            }, function ($query) {
                return $query->select('insights.insight as insight');
            })
            ->distinct()
            ->get();

        // Update the query to use the new model
        $archetypeCareers = ArchetypeCareer::where('archetype', $Archetype)->get();

        return Inertia::render('Result/Personality', [
            "ArchetypeData" => $ArchetypeData,
            'firstScore' => $firstScore->scores,
            'Insights' => $insights,
        ]);
    }
}
