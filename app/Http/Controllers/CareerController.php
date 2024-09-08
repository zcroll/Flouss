<?php

namespace App\Http\Controllers;

use App\Models\JobInfo;
use App\Models\OccupationData;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

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

    public function workActivities(string $job): Response
    {
        $occupation = OccupationData::where('title', str_replace('-', ' ', $job))->firstOrFail();

        $workActivitiesData = DB::table('content_model_reference')
            ->joinSub(
                DB::table('work_activities')
                    ->select(DB::raw('DISTINCT SUBSTRING(element_id, 1, 7) as element_id'), 'data_value')
                    ->where('onetsoc_code', $occupation->onetsoc_code)
                    ->orderByDesc('data_value')
                    ->groupBy('element_id', 'data_value'),
                'work_activities_distinct',
                'work_activities_distinct.element_id',
                '=',
                'content_model_reference.element_id'
            )
            ->select('content_model_reference.element_name')
            ->orderByDesc('work_activities_distinct.data_value')
            ->get();

        return Inertia::render('career/WorkActivities', [
            'occupation' => $occupation,
            'workActivities' => $workActivitiesData,
        ]);
    }
}
