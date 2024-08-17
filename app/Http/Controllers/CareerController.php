<?php

namespace App\Http\Controllers;

use App\Models\ContentModelReference;
use App\Models\TechnologySkill;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\OccupationData;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index($job): \Inertia\Response
    {
        $occupation = OccupationData::where('title', $job)->first();
        $onetsoc_code = $occupation->onetsoc_code;

        $knowledgeData = DB::table('knowledge')
            ->join('content_model_reference', 'knowledge.element_id', '=', 'content_model_reference.element_id')
            ->where('knowledge.onetsoc_code', $onetsoc_code)
            ->where('knowledge.scale_id', 'LV')
            ->select('content_model_reference.element_name', 'content_model_reference.description')
            ->get();

        $abilitiesData = DB::table('abilities')
            ->join('content_model_reference', 'abilities.element_id', '=', 'content_model_reference.element_id',)
            ->where('abilities.scale_id', '=', 'IM')
            ->where('abilities.onetsoc_code', $onetsoc_code)
            ->where('abilities.scale_id', 'LV')

            ->select('content_model_reference.element_id','content_model_reference.element_name', 'content_model_reference.description')
            ->get();
//        ds($abilitiesData);

        $technologyData = OccupationData::with('Technology')->where('title',$job)->get();


       ds($technologyData);

        return Inertia::render('career/OverView', [
            'occupation' => $occupation,
            'knowledge' => $knowledgeData,
            'activities' => $abilitiesData,
            'technology' => $technologyData,
        ]);
    }
}
