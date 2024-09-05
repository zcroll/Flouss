<?php

namespace App\Http\Controllers;

use App\Models\JobInfo;
use App\Models\JobInfoDetail;
use App\Models\JobName;
use App\Models\TechnologySkill;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\OccupationData;
use Illuminate\Http\Request;

class CareerController extends Controller
{



    public function index($job): \Inertia\Response
    {
//        $job = str_replace('-', ' ', $job);

         $occupation = JobInfo::where('slug', $job)->first();

    $occupation->load('jobInfoDetail', 'jobInfoDuties' ,'jobInfoTypes','workplaces');

    $jobInfoDetail =  $occupation->jobInfoDetail;
    $jobInfoDuties =  $occupation->jobInfoDuties ;
    $jobInfoTypes =  $occupation->jobInfoTypes ;
    $jobInfoWorkspace = $occupation->workplaces;




        return Inertia::render('career/OverView', [
            'occupation' => $occupation,
            'jobInfoDetail' => $jobInfoDetail,
            'jobInfoDuties' => $jobInfoDuties,
            'jobInfoTypes' => $jobInfoTypes,
            'workplaces' => $jobInfoWorkspace

        ]);
    }

    public function workEnvironments($job): \Inertia\Response
    {
        $occupation = JobInfo::where('slug', $job)->first();
        ds(['the occupattion'=>$occupation]);
        $occupation->load('workEnvironments');
        $workEnvironments = $occupation->workEnvironments;
             ds($workEnvironments);

        return Inertia::render('career/workEnvironments', [
            'occupation' => $occupation,
            'workEnvironments' => $workEnvironments,
        ]);
    }

    public function personality($job): \Inertia\Response
    {
        $occupation = JobInfo::where('slug', $job)->first();
        $occupation->load('personalityTraits','personalityDetails');
        $personalityTraits = $occupation->personalityTraits;
        $personalityDetails = $occupation->personalityDetails;

        ds($personalityTraits ,$personalityDetails);

        return Inertia::render('career/personality', [
            'occupation' => $occupation,
            'personalityTraits' => $personalityTraits,
            'personalityDetails' => $personalityDetails,
        ]);
    }





    public function workActivities($job): \Inertia\Response
    {
        $job = str_replace('-', ' ', $job);

        $occupation = OccupationData::where('title', $job)->first();

        if (! $occupation) {
            abort(404, 'Occupation not found');
        }

        $onetsoc_code = $occupation->onetsoc_code;

        $workActivitiesData = DB::table('content_model_reference')
            ->joinSub(function ($query) use ($onetsoc_code) {
                $query->select(DB::raw('DISTINCT SUBSTRING(work_activities.element_id, 1, 7) as element_id'), 'work_activities.data_value')
                    ->from('work_activities')
                    ->where('work_activities.onetsoc_code', $onetsoc_code)
                    ->orderBy('work_activities.data_value', 'desc')
                    ->groupBy('work_activities.element_id', 'work_activities.data_value');
            }, 'work_activities_distinct', 'work_activities_distinct.element_id', '=', 'content_model_reference.element_id')
            ->select('content_model_reference.element_name')
            ->orderBy('work_activities_distinct.data_value', 'desc')
            ->get();
      ds($workActivitiesData);
        return Inertia::render('career/WorkActivities', [
            'occupation' => $occupation,
            'workActivities' => $workActivitiesData,
        ]);
    }
}
