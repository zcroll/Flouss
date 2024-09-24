<?php

namespace App\Http\Controllers\Degree;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DegreeController extends Controller
{
    public function index(string $slug): Response
    {
        $degree = Degree::with(['degreeDescription', 'degreeSkills', 'degreeJobs'])
            ->where('slug', $slug)
            ->firstOrFail();



        return Inertia::render('degree/Overview', [
            'degree' => $degree,
            'degreeDescription' => $degree->degreeDescription,
            'degreeSkills' => $degree->degreeSkills,
            'degreeJobs' => $degree->degreeJobs,
        ]);
    }

    public function skills(string $slug): Response
    {
        $degree = Degree::with('degreeSkills')
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('degree/Skills', [
            'degree' => $degree,
            'degreeSkills' => $degree->degreeSkills,
        ]);
    }

    public function jobs(string $slug): Response
    {
        $degree = Degree::with('degreeJobs')
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('degree/Jobs', [
            'degree' => $degree,
            'degreeJobs' => $degree->degreeJobs,
        ]);
    }

    public function howToObtain(string $slug): Response
    {
        $degree = Degree::where('slug', $slug)->firstOrFail();
        $requirements = DB::table('degree_requirements')
            ->where('degree_id', $degree->id)
            ->get();

        return Inertia::render('degree/HowToObtain', [
            'degree' => $degree,
            'requirements' => $requirements,
        ]);
    }
}