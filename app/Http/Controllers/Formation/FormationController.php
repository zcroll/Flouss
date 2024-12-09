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
        // Base query for formations
        $query = Formation::query()
            ->when($request->search, function ($query, $search) {
                $query->where('nom', 'like', "%{$search}%");
            })
            ->when($request->niveau, function ($query, $niveau) {
                $query->where('niveau', $niveau);
            })
            ->when($request->disciplines, function ($query, $disciplines) {
                $query->whereIn('discipline', (array)$disciplines);
            })
            ->when($request->region, function ($query, $region) {
                $query->where('region', $region);
            })
            ->when($request->etablissements, function ($query, $etablissements) {
                $query->whereIn('type_etablissement', (array)$etablissements);
            })
            ->when($request->diplomas, function ($query, $diplomas) {
                $query->whereIn('diploma', (array)$diplomas);
            });

        // Get paginated formations
        $formations = $query->paginate(12)->withQueryString();

        // Get unique type_etablissement values for filter
        $etablissements = Formation::query()
            ->select('type_etablissement')
            ->distinct()
            ->whereNotNull('type_etablissement')
            ->orderBy('type_etablissement')
            ->pluck('type_etablissement')
            ->map(function($type) {
                return [
                    'id' => $type,
                    'nom' => $type
                ];
            })
            ->values()
            ->all();
        
        // Get unique diplomas
        $diplomas = Formation::query()
            ->select('diploma')
            ->distinct()
            ->whereNotNull('diploma')
            ->orderBy('diploma')
            ->pluck('diploma')
            ->filter()
            ->values()
            ->all();
            
        // Get unique disciplines
        $disciplines = Formation::query()
            ->select('discipline')
            ->distinct()
            ->whereNotNull('discipline')
            ->orderBy('discipline')
            ->pluck('discipline')
            ->filter()
            ->values()
            ->all();

        // Debug log
        \Log::info('Request filters:', $request->all());
        \Log::info('Disciplines filter:', ['disciplines' => $disciplines]);

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