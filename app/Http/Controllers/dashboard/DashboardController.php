<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Favorite;
use App\Models\JobInfo;
use App\Models\Result;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $favoriteJobs = DB::table('favorites')
            ->where('favorites.user_id', $user->id)
            ->where('favorites.favoritable_type', 'App\Models\JobInfo')
            ->join('job_infos', 'favorites.favoritable_id', '=', 'job_infos.id')
            ->select([
                'favorites.id',
                'job_infos.name',
                'job_infos.slug',
                'job_infos.image'
            ])
            ->limit(6)
            ->get();

        $favoriteDegrees = DB::table('favorites')
            ->where('favorites.user_id', $user->id)
            ->where('favorites.favoritable_type', 'App\Models\Degree')
            ->join('degrees', 'favorites.favoritable_id', '=', 'degrees.id')
            ->select([
                'favorites.id',
                'degrees.name',
                'degrees.slug', 
                'degrees.image'
            ])
            ->get();

        $hasResult = $user->hasResult();

        $archetype = null;
        $archetypeDiscovery = null;
        $topTraits = [];
        $topJobs = [];

        if ($hasResult) {
            $result = Result::where('user_id', $user->id)->latest()->first();

            if (!empty($result->Archetype)) {
                $archetype = $result->Archetype;
                $archetypeDiscovery = DB::table('archetype_discoveries')
                    ->where('slug', '=', strtolower($archetype[0]))
                    ->select(['slug', 'rarity_string', 'rationale', 'scales'])
                    ->first();
            }

            if (!empty($result->scores)) {
                $decodedScores = json_decode($result->scores, true);
                $topTraits = collect($decodedScores)->take(3)->map(function ($score, $trait) {
                    return [
                        'name' => $trait,
                        'score' => $score
                    ];
                })->values()->toArray();
            }

            if (!empty($result->jobs)) {
                $decodedJobs = json_decode($result->jobs, true, 512, JSON_THROW_ON_ERROR);
                
                if (isset($decodedJobs['job_matches'])) {
                    $jobsCollection = collect($decodedJobs['job_matches']);
                    $jobIds = $jobsCollection->pluck('job_id')->toArray();

                    $topJobs = JobInfo::whereIn('id', $jobIds)
                        ->select('id', 'name', 'slug', 'image')
                        ->limit(6)
                        ->get();
                }
            }
        }
        ds([
            'hasResult' => $hasResult,
            'favoriteJobs' => $favoriteJobs,
            'favoriteDegrees' => $favoriteDegrees,
            'archetype' => $archetypeDiscovery,
            'topTraits' => $topTraits,
            'topJobs' => $topJobs
        ]);

        return Inertia::render('Dashboard', [
            'hasResult' => $hasResult,
            'favoriteJobs' => $favoriteJobs,
            'favoriteDegrees' => $favoriteDegrees,
            'archetype' => $archetypeDiscovery,
            'topTraits' => $topTraits,
            'topJobs' => $topJobs
        ]);

    }


}
