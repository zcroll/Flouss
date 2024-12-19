<?php

namespace App\Http\Controllers\Formation;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FormationController extends Controller
{
    public function index(Request $request)
    {
        $formations = Formation::query()
            ->filter($request->only([
                'search',
                'etablissements',
                'diplomas',
                'disciplines',
                'region'
            ]))
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Formations/Index', [
            'formations' => $formations,
            'filters' => $request->only([
                'search',
                'etablissements',
                'diplomas',
                'disciplines',
                'region'
            ]),
            'filterOptions' => Formation::getFilterOptions()
        ]);
    }

    public function show(Formation $formation)
    {
        return Inertia::render('Formations/Show', [
            'formation' => $formation->load('degrees')
        ]);
    }
} 