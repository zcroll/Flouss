<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use Illuminate\Http\Request;

class FilterOptionsController extends Controller
{
    public function getDisciplines(Request $request)
    {
        $etablissement = $request->input('etablissement');
        
        $disciplines = Formation::disciplinesForEtablissement($etablissement)
            ->pluck('discipline')
            ->map(function($discipline) {
                return [
                    'value' => $discipline,
                    'label' => $discipline
                ];
            });

        return response()->json($disciplines);
    }

    public function getDiplomas(Request $request)
    {
        $etablissement = $request->input('etablissement');
        $discipline = $request->input('discipline');

        $diplomas = Formation::diplomasForEtablissementAndDiscipline($etablissement, $discipline)
            ->pluck('diploma')
            ->map(function($diploma) {
                return [
                    'value' => $diploma,
                    'label' => $diploma
                ];
            });

        return response()->json($diplomas);
    }
} 