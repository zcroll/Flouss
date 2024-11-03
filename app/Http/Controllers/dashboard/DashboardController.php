<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Favorite;
use App\Models\JobInfo;
use Illuminate\Support\Facades\DB;

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

        return Inertia::render('Dashboard', [
            'hasResult' => $user->hasResult(),
            'favoriteJobs' => $favoriteJobs,
            'favoriteDegrees' => $favoriteDegrees
        ]);
    }
}
