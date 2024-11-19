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
    protected function getLocalizedColumn($model, $baseColumn)
    {
        $locale = app()->getLocale();
        $localizedColumn = $locale === 'fr' ? $baseColumn . '_fr' : $baseColumn;
        
        return $model->$localizedColumn ?? $model->$baseColumn;
    }

    /**
     * @throws \JsonException
     */
    public function results() : \Inertia\Response|\Illuminate\Http\RedirectResponse
    {
        $userId = Auth::id();
        $firstScore = ResultResource::collection(Result::with('user')->where('user_id', $userId)->latest()->get())->first();

        if (!$firstScore) {
            return to_route('dashboard');
        }

        $archetype = $firstScore->Archetype ?? null;

        $jobs = null;
        if (!empty($firstScore->jobs)) {
            $decodedJobs = json_decode($firstScore->jobs, true, 512, JSON_THROW_ON_ERROR);
            
            if (isset($decodedJobs['job_matches'])) {
                $jobIds = collect($decodedJobs['job_matches'])->pluck('job_id');
                $jobs = JobInfo::whereIn('id', $jobIds)
                    ->get()
                    ->map(function ($job) {
                        return [
                            'id' => $job->id,
                            'name' => $this->getLocalizedColumn($job, 'name'),
                            'slug' => $job->slug,
                            'image' => $job->image
                        ];
                    });
            }
        }

        $Archetype = DB::table('persona')->where('name', $archetype[0] ?? '')->first();
        $archetypeDiscovery = DB::table('archetype_discoveries')->where('slug', '=', strtolower($archetype[0]))->first();

        if ($firstScore) {
            return Inertia::render('Result/Results', [
                'userId' => $firstScore->uuid,
                'jobs' => $jobs,
                'Archetype' => $archetypeDiscovery,
                'archetypeDiscovery' => $archetypeDiscovery,
            ]);
        }

        return to_route('dashboard');
    }

    public function personality($id): \Inertia\Response
    {
        $userId = Auth::id();
        $scores = ResultResource::collection(Result::with('user')->where('user_id', $userId)->get());

        if ($scores->isEmpty()) {
            return to_route('dashboard');
        }

        $firstScore = $scores->first();
        ds($firstScore->scores);
        
        $archetype = null;
        if (!empty($firstScore->Archetype)) {
            $archetype = $firstScore->Archetype;
        }
        ds(['archetypetest'=>$archetype[0]]);

        $ArchetypeData = DB::table('persona')->where('name', '=',$archetype[0])->first();
        ds(['ArchetypeData'=>$ArchetypeData]);

        $insights = $ArchetypeData ? Insight::where('persona_id', $ArchetypeData->id)
            ->get()
            ->groupBy('insight_category_slug')
            ->map(function ($group) {
                return $group->map(function ($insight) {
                    return $this->getLocalizedColumn($insight, 'insight');
                });
            }) : collect([]);

        $archetypeDiscovery = DB::table('archetype_discoveries')->where('slug', '=', strtolower($archetype[0]))->first();

        ds(['archetypeDiscovery'=>$archetypeDiscovery]);
     
        return Inertia::render('Result/StrategistDescription', [
            "ArchetypeData" => $ArchetypeData ?? [],
            'firstScore' => $firstScore->scores ?? [],
            'Insights' => $insights ?? [],
            'archetypeDiscovery' => $archetypeDiscovery ?? [],
        ]);
    }
}
