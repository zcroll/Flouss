<?php

namespace App\Http\Controllers\Degree;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use App\Models\Formation;
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

        $degree = Degree::with('degreeSkills_v3')
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

        $degree = Degree::where('slug', $slug)->firstOrFail();

        $isFavorited = auth()->check() ?
            $degree->favorites()->where('user_id', auth()->id())->exists() :
            false;

        $formations = Formation::whereHas('degrees', function($query) use ($degree) {
            $query->where('degrees.id', $degree->id);
        })->get();

        return Inertia::render('degree/HowToObtain', [
            'degree' => [
                'id' => $degree->id,
                'name' => $degree->name,
                'slug' => $degree->slug,
                'image' => $degree->image,
                'is_favorited' => $isFavorited,
            ],
            'formations' => $formations->map(function ($formation) {
                return [
                    'id' => $formation->id,
                    'nom' => $formation->nom,
                    'diploma' => $formation->diploma,
                    'niveau' => $formation->niveau,
                    'type_etablissement' => $formation->type_etablissement,
                    'ville' => $formation->ville,
                    'discipline' => $formation->discipline,
                    'region' => $formation->region,
                    'similarity_score' => $formation->degrees->first()->pivot->similarity_score
                ];
            }),
        ]);
    }
}
