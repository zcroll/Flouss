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

        $knowledgeData = DB::table('content_model_reference')
            ->joinSub(function ($query) use ($onetsoc_code) {
                $query->select(DB::raw('DISTINCT SUBSTRING(knowledge.element_id, 1, 7) as element_id'), 'knowledge.data_value')
                    ->from('knowledge')
                    ->where('knowledge.onetsoc_code', $onetsoc_code)
                    ->where('knowledge.scale_id', 'LV');
            }, 'knowledge_distinct', 'knowledge_distinct.element_id', '=', 'content_model_reference.element_id')
            ->select('content_model_reference.element_name')
            ->orderBy('knowledge_distinct.data_value', 'desc')
            ->limit(4)
            ->get();



        $abilitiesData = DB::table('content_model_reference')
            ->joinSub(function ($query) use ($onetsoc_code) {
                $query->select(DB::raw('DISTINCT SUBSTRING(abilities.element_id, 1, 9) as element_id'), 'abilities.data_value')
                    ->from('abilities')
                    ->where('abilities.onetsoc_code', $onetsoc_code)
                    ->where('abilities.scale_id', 'LV');
            }, 'abilities_distinct', 'abilities_distinct.element_id', '=', 'content_model_reference.element_id')
            ->select('content_model_reference.element_name')
            ->orderBy('abilities_distinct.data_value', 'desc')
            ->limit(4)
            ->get();


//        ds($abilitiesData);df




        return Inertia::render('career/OverView', [
            'occupation' => $occupation,
            'knowledge' => $knowledgeData,
            'abilities' => $abilitiesData,

        ]);
    }
}
