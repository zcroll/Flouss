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
//        ds($abilitiesData);df


        $technologySkillsData = DB::table('technology_skills')
            ->join('unspsc_reference', 'technology_skills.commodity_code', '=', 'unspsc_reference.commodity_code')
            ->where('technology_skills.onetsoc_code', $onetsoc_code)
            ->where('technology_skills.hot_technology', 'Y')
            ->select('technology_skills.onetsoc_code', 'technology_skills.example', 'technology_skills.hot_technology', 'unspsc_reference.commodity_title')

            ->get();

        ds($technologySkillsData->toArray() , $onetsoc_code);


        return Inertia::render('career/OverView', [
            'occupation' => $occupation,
            'knowledge' => $knowledgeData,
            'activities' => $abilitiesData,

        ]);
    }
}
