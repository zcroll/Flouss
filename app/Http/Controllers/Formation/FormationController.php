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
        $query = Formation::query()
            ->when($request->search, function ($query, $search) {
                $query->where('nom', 'like', "%{$search}%");
            })
            ->when($request->niveau, function ($query, $niveau) {
                $query->where('niveau', $niveau);
            })
            ->when($request->discipline, function ($query, $discipline) {
                $query->where('discipline', $discipline);
            })
            ->when($request->region, function ($query, $region) {
                $query->where('region', $region);
            })
            ->when($request->etablissement, function ($query, $etablissement) {
                $query->where('etablissement_id', $etablissement);
            })
            ->when($request->diploma, function ($query, $diploma) {
                $query->where('diploma', $diploma);
            });

        $formations = $query->paginate(12)->withQueryString();

        // Get data for multiselect options
        $etablissements = Etablissement::select('id', 'nom')->get();
        
        $diplomas = Formation::distinct()
            ->whereNotNull('diploma')
            ->pluck('diploma')
            ->filter()
            ->values();
            
        $disciplines = Formation::distinct()
            ->whereNotNull('discipline')
            ->pluck('discipline')
            ->filter()
            ->values();

        return Inertia::render('Formations/Index', [
            'formations' => $formations,
            'filters' => $request->only(['search', 'niveau', 'discipline', 'region', 'etablissement', 'diploma']),
            // Add these for the multiselect options
            'etablissements' => $etablissements,
            'diplomas' => $diplomas,
            'disciplines' => $disciplines,
        ]);
    }

    public function show(Formation $formation)
    {
        return Inertia::render('Formations/Show', [
            'formation' => $formation->load('degrees')
        ]);
    }
} 