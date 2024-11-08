<?php

namespace App\Http\Controllers\Career;

use App\Http\Controllers\Controller;
use App\Http\Resources\Career\JobInfoResource;
use App\Http\Resources\Career\JobInfoDetailResource;
use App\Http\Resources\Career\WorkEnvironmentResource;
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
        $occupation = JobInfo::with(['jobInfoDetail', 'jobInfoDuties', 'jobInfoTypes', 'workplaces'])
            ->where('slug', $job)
            ->firstOrFail();


            

        $isFavorited = auth()->check() ? 
            $occupation->favorites()->where('user_id', auth()->id())->exists() : 
            false;

        return Inertia::render('career/OverView', [
            'occupation' => new JobInfoResource($occupation),
            'jobInfoDetail' => JobInfoDetailResource::collection($occupation->jobInfoDetail),
            'jobInfoDuties' => $occupation->jobInfoDuties->map(function ($duty) {
                $locale = app()->getLocale();
                return [
                    'duty_description' => $locale === 'fr' ? $duty->duty_description_fr : $duty->duty_description,
                ];
            }),
            'jobInfoTypes' => $occupation->jobInfoTypes->unique('type_name')->map(function ($type) {
                $locale = app()->getLocale();
                return [
                    'type_name' => $locale === 'fr' ? $type->type_name_fr : $type->type_name,
                    'type_description' => $locale === 'fr' ? $type->type_description_fr : $type->type_description,
                ];
            }),
            'workplaces' => $occupation->workplaces->map(function ($workplace) {
                $locale = app()->getLocale();
                return [
                    'content' => $locale === 'fr' ? $workplace->content_fr : $workplace->content,
                ];
            }),
            'is_favorited' => $isFavorited,
        ]);
    }
    public function workEnvironments(string $job): Response
    {
        $occupation = JobInfo::with('workEnvironments')
            ->where('slug', $job)
            ->firstOrFail();

        $isFavorited = auth()->check() ? 
            $occupation->favorites()->where('user_id', auth()->id())->exists() : 
            false;

        return Inertia::render('career/workEnvironments', [
            'occupation' => new JobInfoResource($occupation),
            'workEnvironments' => WorkEnvironmentResource::collection($occupation->workEnvironments->unique('name')),
            'is_favorited' => $isFavorited,
        ]);
    }

    public function personality(string $job): Response
    {
        $locale = app()->getLocale();

        $occupation = JobInfo::where('slug', $job)->firstOrFail();

        $isFavorited = auth()->check() ? 
            $occupation->favorites()->where('user_id', auth()->id())->exists() : 
            false;

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
                'is_favorited' => $isFavorited,
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

        $isFavorited = auth()->check() ? 
            $occupation->favorites()->where('user_id', auth()->id())->exists() : 
            false;

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
                'is_favorited' => $isFavorited,
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
            ->with(['jobInfoDuties', 'workEnvironments'])
            ->firstOrFail();
        
        $isFavorited = auth()->check() ? 
            $career->favorites()->where('user_id', auth()->id())->exists() : 
            false;

        return Inertia::render('career/Show', [
            'career' => array_merge($career->toArray(), ['is_favorited' => $isFavorited])
        ]);
    }
}
