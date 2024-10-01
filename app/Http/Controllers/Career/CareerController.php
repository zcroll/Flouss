<?php

namespace App\Http\Controllers\Career;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use App\Models\DegreeJob;
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

        $occupation = JobInfo::with([
            'jobSteps',
            'jobCertifications',
            'jobAssociations',
            'jobDegrees',
            'howToBecome',
        ])->where('slug', $job)->firstOrFail();

        $degrees = $occupation->jobDegrees->map(function ($jobDegree) {
            $degree = Degree::where('name', $jobDegree->degree_title)->first();
            return [
                'title' => $jobDegree->degree_title,
                'image' => $degree ? $degree->image : null,
                'slug' => $degree ? $degree->slug : null,
                'name' => $degree ? $degree->name : null,
            ];
        });

        ds($occupation->jobSteps->isEmpty());

        return Inertia::render('career/HowToBecome', [
            'occupation' => [
                'name' => $locale === 'fr' ? $occupation->name_fr : $occupation->name,
                'slug' => $occupation->slug,
                'image' => $occupation->image,
            ],
            'jobSteps' => $occupation->jobSteps,
            'jobCertifications' => $occupation->jobCertifications,
            'jobAssociations' => $occupation->jobAssociations,
            'jobDegrees' => $degrees,
            'howToBecome' => $occupation->howToBecome,
            'disableStepsLink' => $occupation->jobSteps->isEmpty(),
       
        ]);
    }
}
