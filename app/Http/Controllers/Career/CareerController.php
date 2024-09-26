<?php

namespace App\Http\Controllers\Career;

use App\Http\Controllers\Controller;
use App\Models\JobInfo;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CareerController extends Controller
{
    public function index(string $job): Response
    {
        $locale = app()->getLocale();
        $nameColumn = $locale === 'fr' ? 'name_fr' : 'name';
        $descriptionColumn = $locale === 'fr' ? 'description_fr' : 'description';
        
        $occupation = JobInfo::with(['jobInfoDetail', 'jobInfoDuties', 'jobInfoTypes', 'workplaces'])
            ->where('slug', $job)
            ->firstOrFail();

        return Inertia::render('career/OverView', [
            'occupation' => [
                'name' => $locale === 'fr' ? $occupation->name_fr : $occupation->name,
                'slug' => $occupation->slug,
                'image' => $occupation->image,
            ],
            'jobInfoDetail' => $occupation->jobInfoDetail->map(function ($detail) use ($locale) {
                return [
                    'role_description_main' => $locale === 'fr' ? $detail->role_description_main_fr : $detail->role_description_main,
                    'role_description_secondary' => $locale === 'fr' ? $detail->role_description_secondary_fr : $detail->role_description_secondary,
                ];
            }),
            'jobInfoDuties' => $occupation->jobInfoDuties->map(function ($duty) use ($locale) {
                return [
                    'duty_description' => $locale === 'fr' ? $duty->duty_description_fr : $duty->duty_description,
                ];
            }),
            'jobInfoTypes' => $occupation->jobInfoTypes->map(function ($type) use ($locale) {
                return [
                    'type_name' => $locale === 'fr' ? $type->type_name_fr : $type->type_name,
                    'type_description' => $locale === 'fr' ? $type->type_description_fr : $type->type_description,
                ];
            }),
            'workplaces' => $occupation->workplaces->map(function ($workplace) use ($locale) {
                return [
                    'content' => $locale === 'fr' ? $workplace->content_fr : $workplace->content,
                ];
            }),
        ]);
    }

    public function workEnvironments(string $job): Response
    {
        $locale = app()->getLocale();
        $nameColumn = $locale === 'fr' ? 'name_fr' : 'name';
        $descriptionColumn = $locale === 'fr' ? 'description_fr' : 'description';

        $occupation = JobInfo::with('workEnvironments')
            ->where('slug', $job)
            ->firstOrFail();

        return Inertia::render('career/workEnvironments', [
            'occupation' => [
                'name' => $locale === 'fr' ? $occupation->name_fr : $occupation->name,
                'slug' => $occupation->slug,
                'image' => $occupation->image,
            ],    
            'workEnvironments' => $occupation->workEnvironments->map(function ($environment) use ($nameColumn, $descriptionColumn) {
                return [
                    'name' => $environment->$nameColumn,
                    'category' => $environment->category,
                    'score' => $environment->score,
                    'description' => $environment->$descriptionColumn,
                ];
            }),
        ]);
    }

    public function personality(string $job): Response
    {
        $locale = app()->getLocale();
        $careerNameColumn = $locale === 'fr' ? 'career_name_fr' : 'career_name';
        $descriptionColumn = $locale === 'fr' ? 'description_fr' : 'description';

        $occupation = JobInfo::with(['personalityTraits', 'personalityDetails'])
            ->where('slug', $job)
            ->firstOrFail();

        return Inertia::render('career/personality', [
            'occupation' => [
                'name' => $locale === 'fr' ? $occupation->name_fr : $occupation->name,
                'slug' => $occupation->slug,
                'image' => $occupation->image,
            ],
            'personalityTraits' => $occupation->personalityTraits,
            'personalityDetails' => $occupation->personalityDetails->map(function ($detail) use ($careerNameColumn, $descriptionColumn) {
                return [
                    'career_name' => $detail->$careerNameColumn,
                    'description' => $detail->$descriptionColumn,
                ];
            }),
        ]);
    }

    public function howToBecome(string $job): Response
    {
        $locale = app()->getLocale();
        $nameColumn = $locale === 'fr' ? 'name_fr' : 'name';
        $descriptionColumn = $locale === 'fr' ? 'secondary_description_fr' : 'secondary_description';

        $occupation = JobInfo::where('slug', $job)->firstOrFail();
        $degrees = DB::table('job_degree_relations')
            ->join('degrees', 'job_degree_relations.degree_id', '=', 'degrees.id')
            ->join('degree_descriptions', 'degrees.id', '=', 'degree_descriptions.degree_id')
            ->where('job_degree_relations.job_id', $occupation->id)
            ->select("degrees.$nameColumn as name", "degree_descriptions.$descriptionColumn as description")
            ->get();

        $formations = DB::table('job_formations')
            ->join('formation', 'job_formations.formation_id', '=', 'formation.id')
            ->where('job_formations.job_id', $occupation->id)
            ->select('formation.*', 'job_formations.similarity_score')
            ->orderByDesc('job_formations.similarity_score')
            ->get();

        return Inertia::render('career/HowToBecome', [
            'occupation' => $occupation,
            'degrees' => $degrees,
            'formations' => $formations,
        ]);
    }
}
