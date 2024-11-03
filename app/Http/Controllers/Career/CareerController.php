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
                'id' => $occupation->id,
                'name' => $locale === 'fr' ? $occupation->name_fr : $occupation->name,
                'slug' => $occupation->slug,
                'image' => $occupation->image,
                'is_favorited' => $occupation->isFavorite(),
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
                'id' => $occupation->id,
                'name' => $locale === 'fr' ? $occupation->name_fr : $occupation->name,
                'slug' => $occupation->slug,
                'image' => $occupation->image,
                'is_favorited' => $occupation->isFavorite(),
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

        $occupation = JobInfo::where('slug', $job)->firstOrFail();

        $personalityTraits = DB::table('personality_traits')->where('job_id', $occupation->id)->get();
        

        ds([ 'personalityTraits' => $personalityTraits ]);
        $personalityDetails = DB::table('personality_details')->where('job_id', $occupation->id)->get()->map(function ($detail) {
            return [
                'career_name' => $detail->career_name,
                'description' => $detail->description,
                'trait_type' => $detail->trait_type,
            ];
        });
        ds([ 'personalityDetails' => $personalityDetails ]);

        return Inertia::render('career/personality', [
            'occupation' => [
                'id' => $occupation->id,
                'name' => $locale === 'fr' ? $occupation->name_fr : $occupation->name,
                'slug' => $occupation->slug,
                'image' => $occupation->image,
                'is_favorited' => $occupation->isFavorite(),
            ],
            'personalityTraits' => $personalityTraits,
            'personalityDetails' => $personalityDetails,
        ]);
    }

    public function howToBecome(string $job): Response
    {
        $locale = app()->getLocale();

        $occupation = JobInfo::with([
            'jobSteps',
            'jobCertifications',
            'jobAssociations',
            'howToBecome',
        ])->where('slug', $job)->firstOrFail();

        $degrees = DegreeJob::where('job_id', $occupation->id)
            ->with('degree')
            ->get()
            ->map(function ($jobDegree) {
                $degree = $jobDegree->degree;
                return [
                    'title' => $degree->name,
                    'image' => $degree->image,
                    'slug' => $degree->slug,
                    'name' => $degree->name,
                ];
            });

        ds($occupation->jobSteps->isEmpty());

        return Inertia::render('career/HowToBecome', [
            'occupation' => [
                'id' => $occupation->id,
                'name' => $locale === 'fr' ? $occupation->name_fr : $occupation->name,
                'slug' => $occupation->slug,
                'image' => $occupation->image,
                'is_favorited' => $occupation->isFavorite(),
            ],
            'jobSteps' => $occupation->jobSteps,
            'jobCertifications' => $occupation->jobCertifications,
            'jobAssociations' => $occupation->jobAssociations,
            'jobDegrees' => $degrees,
            'howToBecome' => $occupation->howToBecome,
            'disableStepsLink' => $occupation->jobSteps->isEmpty(),
        ]);
    }

    public function show($slug)
    {
        $career = JobInfo::where('slug', $slug)
            ->with(['jobInfoDuties', 'workEnvironments', /* other relationships */])
            ->first();
        
        $career->is_favorite = $career->isFavorite();

        return Inertia::render('career/Show', [
            'career' => $career
        ]);
    }
}
