<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FormationFilterController extends Controller
{
    public function index(Request $request)
    {
        $query = Formation::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nom', 'like', "%{$search}%")
                  ->orWhere('nomAr', 'like', "%{$search}%");
        }

        if ($request->has('etablissement')) {
            $etablissementId = $request->input('etablissement');
            $query->where('etablissement_id', $etablissementId);
        }

        $formations = $query->with('etablissement')->paginate(10);
        $etablissements = Etablissement::select('id', 'nom', 'nomAr')->get();

        return Inertia::render('Formations/Index', [
            'formations' => $formations,
            'etablissements' => $etablissements,
            'filters' => $request->only(['search', 'etablissement']),
        ]);
    }

    public function filter(Request $request)
    {
        $query = Formation::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nom', 'like', "%{$search}%")
                  ->orWhere('nomAr', 'like', "%{$search}%");
        }

        if ($request->has('etablissement')) {
            $etablissementId = $request->input('etablissement');
            $query->where('etablissement_id', $etablissementId);
        }

        $formations = $query->with('etablissement')->paginate(10);

        return response()->json($formations);
    }

    public function getEtablissements()
    {
        $etablissements = Etablissement::select('id', 'nom', 'nomAr')->get();
        return response()->json($etablissements);
    }
}