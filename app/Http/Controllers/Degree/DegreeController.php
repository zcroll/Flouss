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
    protected function getLocalizedColumn($model, $baseColumn)
    {
        $locale = app()->getLocale();
        $localizedColumn = $locale === 'fr' ? $baseColumn . '_fr' : $baseColumn;
        
        return $model->$localizedColumn ?? $model->$baseColumn;
    }

    public function index(string $slug): Response
    {
        $degree = Degree::with(['degreeDescription', 'degreeSkills', 'degreeJobs'])
            ->where('slug', $slug)
            ->firstOrFail();

        $isFavorited = auth()->check() ?
            $degree->favorites()->where('user_id', auth()->id())->exists() :
            false;

        return Inertia::render('degree/Overview', [
            'degree' => [
                'id' => $degree->id,
                'name' => $this->getLocalizedColumn($degree, 'name'),
                'slug' => $degree->slug,
                'image' => $degree->image,
                'degree_level' => $degree->degree_level,
                'satisfaction' => $degree->satisfaction,
                'is_favorited' => $isFavorited,
            ],
            'degreeDescription' => [
                'main_description' => $degree->degreeDescription ? $this->getLocalizedColumn($degree->degreeDescription, 'main_description') : null,
                'secondary_description' => $degree->degreeDescription ? $this->getLocalizedColumn($degree->degreeDescription, 'secondary_description') : null,
            ],
            'degreeSkills' => $degree->degreeSkills->map(function ($skill) {
                return [
                    'skill_description' => $this->getLocalizedColumn($skill, 'skill_description'),
                ];
            }),
            'degreeJobs' => $degree->degreeJobs->map(function ($job) {
                return [
                    'job_title' => $this->getLocalizedColumn($job, 'job_title'),
                    'job_description' => $this->getLocalizedColumn($job, 'job_description'),
                ];
            }),
        ]);
    }

    public function skills(string $slug): Response
    {
        $degree = Degree::with('degreeSkills_v3')
            ->where('slug', $slug)
            ->firstOrFail();

        $isFavorited = auth()->check() ?
            $degree->favorites()->where('user_id', auth()->id())->exists() :
            false;

        return Inertia::render('degree/Skills', [
            'degree' => [
                'id' => $degree->id,
                'name' => $this->getLocalizedColumn($degree, 'name'),
                'slug' => $degree->slug,
                'image' => $degree->image,
                'is_favorited' => $isFavorited,
            ],
            'degreeSkills' => $degree->degreeSkills->map(function ($skill) {
                return [
                    'skill_description' => $this->getLocalizedColumn($skill, 'skill_description'),
                ];
            }),
        ]);
    }

    public function jobs(string $slug): Response
    {
        $degree = Degree::with('degreeJobs')
            ->where('slug', $slug)
            ->firstOrFail();

        $isFavorited = auth()->check() ?
            $degree->favorites()->where('user_id', auth()->id())->exists() :
            false;

        return Inertia::render('degree/Jobs', [
            'degree' => [
                'id' => $degree->id,
                'name' => $this->getLocalizedColumn($degree, 'name'),
                'slug' => $degree->slug,
                'image' => $degree->image,
                'is_favorited' => $isFavorited,
            ],
            'degreeJobs' => $degree->degreeJobs->map(function ($job) {
                return [
                    'job_title' => $this->getLocalizedColumn($job, 'job_title'),
                    'job_description' => $this->getLocalizedColumn($job, 'job_description'),
                ];
            }),
        ]);
    }

    public function howToObtain(string $slug): Response
    {
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
                'name' => $this->getLocalizedColumn($degree, 'name'),
                'slug' => $degree->slug,
                'image' => $degree->image,
                'is_favorited' => $isFavorited,
            ],
            'formations' => $formations->map(function ($formation) {
                return [
                    'id' => $formation->id,
                    'nom' => $this->getLocalizedColumn($formation, 'nom'),
                    'diploma' => $this->getLocalizedColumn($formation, 'diploma'),
                    'niveau' => $this->getLocalizedColumn($formation, 'niveau'),
                    'type_etablissement' => $this->getLocalizedColumn($formation, 'type_etablissement'),
                    'ville' => $this->getLocalizedColumn($formation, 'ville'),
                    'discipline' => $this->getLocalizedColumn($formation, 'discipline'),
                    'region' => $this->getLocalizedColumn($formation, 'region'),
                    'similarity_score' => $formation->degrees->first()->pivot->similarity_score
                ];
            }),
        ]);
    }
}
