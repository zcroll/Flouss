<?php

namespace App\Http\Controllers;

use App\Models\TechnologySkill;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\OccupationData;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index($job): \Inertia\Response
    {
        $job = str_replace('-', ' ', $job);

        ds($job);
        $occupation = OccupationData::where('title', $job)->first();
        $onetsoc_code = $occupation->onetsoc_code;


        $knowledgeData = DB::table('content_model_reference')
            ->joinSub(function ($query) use ($onetsoc_code) {
                $query->select(DB::raw('DISTINCT SUBSTRING(knowledge.element_id, 1, 7) as element_id'), 'knowledge.data_value')
                    ->from('knowledge')
                    ->where('knowledge.onetsoc_code', $onetsoc_code)
                    ->where('knowledge.scale_id', 'LV')
                    ->orderBy('knowledge.data_value', 'desc')
                    ->groupBy('knowledge.element_id', 'knowledge.data_value');
            }, 'knowledge_distinct', 'knowledge_distinct.element_id', '=', 'content_model_reference.element_id')
            ->select('content_model_reference.element_name', 'content_model_reference.description')
            ->orderBy('knowledge_distinct.data_value', 'desc')
            ->limit(10)
            ->get();

//        ds($knowledgeData);

        $abilitiesData = DB::table('content_model_reference')
            ->joinSub(function ($query) use ($onetsoc_code) {
                $query->select(DB::raw('DISTINCT SUBSTRING(abilities.element_id, 1, 7) as element_id'), 'abilities.data_value')
                    ->from('abilities')
                    ->where('abilities.onetsoc_code', $onetsoc_code)
                    ->where('abilities.scale_id', 'LV')
                    ->orderBy('abilities.data_value', 'desc')
                    ->groupBy('abilities.element_id', 'abilities.data_value');
            }, 'abilities_distinct', 'abilities_distinct.element_id', '=', 'content_model_reference.element_id')
            ->select('content_model_reference.element_name')
            ->orderBy('abilities_distinct.data_value', 'desc')
            ->limit(4)
            ->get();

        ds($abilitiesData);

        $technologySkillsData = DB::table('technology_skills')
            ->where('onetsoc_code', $onetsoc_code)
            ->where('hot_technology', 'Y')
            ->select('onetsoc_code', 'example', 'hot_technology')
            ->get();

        #todo

//        $technologySkillsData = DB::table('technology_skills')
//            ->join('unspsc_reference', 'technology_skills.commodity_code', '=', 'unspsc_reference.commodity_code')
//            ->where('technology_skills.onetsoc_code', $onetsoc_code)
//            ->where('technology_skills.hot_technology', 'Y')
//            ->select('technology_skills.onetsoc_code', 'technology_skills.example', 'technology_skills.hot_technology', 'unspsc_reference.commodity_title')
//
//            ->get();

//        ds($technologySkillsData->toArray() , $onetsoc_code);
//        ray($technologySkillsData);

//        ray()->showViews();
        ds(url()->current());

        return Inertia::render('career/OverView', [
            'occupation' => $occupation,
            'knowledge' => $knowledgeData,
            'abilities`' => $abilitiesData,
            'baseUri' => url()->current(), // Ensure this is set correctly


        ]);
    }
    public function abilities($job): \Inertia\Response
    {
        // Convert spaces to hyphens in the URI parameter
        $job = str_replace('-', ' ', $job);

        // Continue using the original $job for database queries
        $occupation = OccupationData::where('title', $job)->first();

        if (!$occupation) {
            // Handle the case where occupation is not found
            abort(404, 'Occupation not found');
        }

        $onetsoc_code = $occupation->onetsoc_code;

        // Abilities Data Query
        $abilitiesData = DB::table('content_model_reference')
            ->joinSub(function ($query) use ($onetsoc_code) {
                $query->select(DB::raw('DISTINCT SUBSTRING(abilities.element_id, 1, 7) as element_id'), 'abilities.data_value')
                    ->from('abilities')
                    ->where('abilities.onetsoc_code', $onetsoc_code)
                    ->where('abilities.scale_id', 'LV')
                    ->orderBy('abilities.data_value', 'desc')
                    ->groupBy('abilities.element_id', 'abilities.data_value');
            }, 'abilities_distinct', 'abilities_distinct.element_id', '=', 'content_model_reference.element_id')
            ->select('content_model_reference.element_name')
            ->orderBy('abilities_distinct.data_value', 'desc')
            ->limit(4)
            ->get();

        return Inertia::render('career/Abilities', [
            'occupation' => $occupation,
            'abilities' => $abilitiesData,
            'baseUri' => url()->current() // Passing the current URI
        ]);
    }
}
