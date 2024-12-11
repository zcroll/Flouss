<?php

namespace App\Http\Controllers\Formation;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FormationController extends Controller
{
    public function index(Request $request)
    {
        // Apply filters to get base query
        $query = Formation::query()->applyFilters($request->all());

        // Get paginated formations
        $formations = $query->paginate(12)->withQueryString();

        // Get filter options based on the current query
        $filterOptions = Formation::getFilterOptions($query);

        return Inertia::render('Formations/Index', [
            'formations' => $formations,
            'filters' => $request->only([
                'search',
                'niveau',
                'disciplines',
                'region',
                'etablissements',
                'diplomas'
            ]),
            'etablissements' => $filterOptions['etablissements'],
            'diplomas' => $filterOptions['diplomas'],
            'disciplines' => $filterOptions['disciplines'],
        ]);
    }

    public function show(Formation $formation)
    {
        return Inertia::render('Formations/Show', [
            'formation' => $formation->load('degrees')
        ]);
    }

    public function getFilterOptions(Request $request)
    {
        $query = Formation::query()->applyFilters($request->all());
        return response()->json(Formation::getFilterOptions($query));
    }
} 