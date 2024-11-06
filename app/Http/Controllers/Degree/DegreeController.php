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
        $locale = app()->getLocale();
        $mainDescriptionColumn = $locale === 'fr' ? 'main_description_fr' : 'main_description';
        $secondaryDescriptionColumn = $locale === 'fr' ? 'secondary_description_fr' : 'secondary_description';
        $jobTitleColumn = $locale === 'fr' ? 'job_title_fr' : 'job_title';
        $jobDescriptionColumn = $locale === 'fr' ? 'job_description_fr' : 'job_description';

        $degree = Degree::with(['degreeDescription', 'degreeSkills', 'degreeJobs'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Check if user is authenticated and if they favorited this degree
        $isFavorited = auth()->check() ? 
            $degree->favorites()->where('user_id', auth()->id())->exists() : 
            false;


        ds($isFavorited);

        return Inertia::render('degree/Overview', [
            'degree' => [
                'id' => $degree->id,
                'name' => $degree->name,
                'slug' => $degree->slug,
                'image' => $degree->image,
                'degree_level' => $degree->degree_level,
                'satisfaction' => $degree->satisfaction,
                'is_favorited' => $isFavorited,
            ],
            'degreeDescription' => [
                'main_description' => $degree->degreeDescription ? $degree->degreeDescription->$mainDescriptionColumn : null,
                'secondary_description' => $degree->degreeDescription ? $degree->degreeDescription->$secondaryDescriptionColumn : null,
            ],
            'degreeSkills' => $degree->degreeSkills->map(function ($skill) use ($locale) {
                return [
                    'skill_description' => $locale === 'fr' ? $skill->skill_description_fr : $skill->skill_description,
                ];
            }),
            'degreeJobs' => $degree->degreeJobs->map(function ($job) use ($jobTitleColumn, $jobDescriptionColumn) {
                return [
                    'job_title' => $job->$jobTitleColumn,
                    'job_description' => $job->$jobDescriptionColumn,
                ];
            }),
        ]);
    }

    public function skills(string $slug): Response
    {
        $locale = app()->getLocale();

        $degree = Degree::with('degreeSkills')
            ->where('slug', $slug)
            ->firstOrFail();

        $isFavorited = auth()->check() ? 
            $degree->favorites()->where('user_id', auth()->id())->exists() : 
            false;

        return Inertia::render('degree/Skills', [
            'degree' => [
                'id' => $degree->id,
                'name' => $degree->name,
                'slug' => $degree->slug,
                'image' => $degree->image,
                'is_favorited' => $isFavorited,
            ],
            'degreeSkills' => $degree->degreeSkills->map(function ($skill) use ($locale) {
                return [
                    'skill_description' => $locale === 'fr' ? $skill->skill_description_fr : $skill->skill_description,
                ];
            }),
        ]);
    }

    public function jobs(string $slug): Response
    {
        $locale = app()->getLocale();

        $degree = Degree::with('degreeJobs')
            ->where('slug', $slug)
            ->firstOrFail();

        $isFavorited = auth()->check() ? 
            $degree->favorites()->where('user_id', auth()->id())->exists() : 
            false;

        ds($isFavorited);

        return Inertia::render('degree/Jobs', [
            'degree' => [
                'id' => $degree->id,
                'name' => $degree->name,
                'slug' => $degree->slug,
                'image' => $degree->image,
                'is_favorited' => $isFavorited,
            ],
            'degreeJobs' => $degree->degreeJobs->map(function ($job) use ($locale) {
                return [
                    'job_title' => $locale === 'fr' ? $job->job_title_fr : $job->job_title,
                    'job_description' => $job->job_description,
                ];
            }),
        ]);
    }

    public function howToObtain(string $slug): Response
    {
        $locale = app()->getLocale();

        $degree = Degree::with(['degreeFormationMatches.formation.etablissement'])
            ->where('slug', $slug)
            ->firstOrFail();

        $isFavorited = auth()->check() ? 
            $degree->favorites()->where('user_id', auth()->id())->exists() : 
            false;

        return Inertia::render('degree/HowToObtain', [
            'degree' => [
                'id' => $degree->id,
                'name' => $degree->name,
                'slug' => $degree->slug,
                'image' => $degree->image,
                'is_favorited' => $isFavorited,
            ],
            'formations' => $degree->degreeFormationMatches->map(function ($match) use ($locale) {
                return [
                    'id' => $match->formation_id,
                    'name' => $locale === 'fr' ? $match->formation_name_fr : $match->formation_name,
                    'similarity_score' => $match->similarity_score,
                    'etablissement' => $match->formation->etablissement,
                    'libelle' => $match->formation->diplomeLibelleFr
                ];
            }),
        ]);
    }
}

