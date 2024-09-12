<?php

namespace App\Http\Controllers;

use App\Models\JobInfo;
use App\Models\OccupationData;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\JobFormation;
use App\Models\Formation;

class CareerController extends Controller
{
    public function index(string $job): Response
    {
        $occupation = JobInfo::with(['jobInfoDetail', 'jobInfoDuties', 'jobInfoTypes', 'workplaces'])
            ->where('slug', $job)
            ->firstOrFail();

        return Inertia::render('career/OverView', [
            'occupation' => $occupation,
            'jobInfoDetail' => $occupation->jobInfoDetail,
            'jobInfoDuties' => $occupation->jobInfoDuties,
            'jobInfoTypes' => $occupation->jobInfoTypes,
            'workplaces' => $occupation->workplaces
        ]);
    }

    public function workEnvironments(string $job): Response
    {
        $occupation = JobInfo::with('workEnvironments')
            ->where('slug', $job)
            ->firstOrFail();

        return Inertia::render('career/workEnvironments', [
            'occupation' => $occupation,
            'workEnvironments' => $occupation->workEnvironments,
        ]);
    }

    public function personality(string $job): Response
    {
        $occupation = JobInfo::with(['personalityTraits', 'personalityDetails'])
            ->where('slug', $job)
            ->firstOrFail();

        return Inertia::render('career/personality', [
            'occupation' => $occupation,
            'personalityTraits' => $occupation->personalityTraits,
            'personalityDetails' => $occupation->personalityDetails,
        ]);
    }

    public function howToBecome(string $job): Response
    {
        $occupation = JobInfo::where('slug', $job)->firstOrFail();
        
        $formations = DB::table('job_formations')
            ->join('formation', 'job_formations.formation_id', '=', 'formation.id')
            ->where('job_formations.job_id', $occupation->id)
            ->select('formation.*', 'job_formations.similarity_score')
            ->orderByDesc('job_formations.similarity_score')
            ->get();

        return Inertia::render('career/HowToBecome', [
            'occupation' => $occupation,
            'formations' => $formations,
        ]);
    }
}
