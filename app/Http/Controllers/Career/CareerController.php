<?php

namespace App\Http\Controllers\Career;

use App\Http\Controllers\Controller;
use App\Http\Resources\Career\JobInfoResource;
use App\Http\Resources\Career\JobInfoDetailResource;
use App\Http\Resources\Career\WorkEnvironmentResource;
use App\Models\Degree;
use App\Models\DegreeJob;
use App\Models\JobInfo;
use App\Models\JobStep;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CareerController extends Controller
{
    protected function getLocalizedColumn($model, $baseColumn)
    {
        $locale = app()->getLocale();
        $localizedColumn = $locale === 'fr' ? $baseColumn . '_fr' : $baseColumn;
        
        return $model->$localizedColumn ?? $model->$baseColumn;
    }

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
                return [
                    'duty_description' => $this->getLocalizedColumn($duty, 'duty_description'),
                ];
            }),
            'jobInfoTypes' => $occupation->jobInfoTypes->unique('type_name')->map(function ($type) {
                return [
                    'type_name' => $this->getLocalizedColumn($type, 'type_name'),
                    'type_description' => $this->getLocalizedColumn($type, 'type_description'),
                ];
            }),
            'workplaces' => $occupation->workplaces->map(function ($workplace) {
                return [
                    'content' => $this->getLocalizedColumn($workplace, 'content'),
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
                'name' => $this->getLocalizedColumn($occupation, 'name'),
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
        $occupation = JobInfo::with([
            'jobSteps',
            'jobAssociations',
            'howToBecome',
        ])->where('slug', $job)->firstOrFail();

        $isFavorited = auth()->check() ? 
            $occupation->favorites()->where('user_id', auth()->id())->exists() : 
            false;

        $degrees = DegreeJob::where('job_id', $occupation->id)
        ->with(['degree' => function($query) {
            $query->has('degreeSkills');
        }])
        ->get()
        ->filter(function ($jobDegree) {
            return !is_null($jobDegree->degree);
        })

            ->map(function ($jobDegree) {
                $degree = $jobDegree->degree;
                return [
                    'title' => $this->getLocalizedColumn($degree, 'name'),
                    'image' => $degree->image,
                    'slug' => $degree->slug,
                    'name' => $this->getLocalizedColumn($degree, 'name'),
                ];
            });

        ds($occupation->jobSteps->isEmpty());

        return Inertia::render('career/HowToBecome', [
            'occupation' => [
                'id' => $occupation->id,
                'name' => $this->getLocalizedColumn($occupation, 'name'),
                'slug' => $occupation->slug,
                'image' => $occupation->image,
                'is_favorited' => $isFavorited,
            ],
            'jobSteps' => $occupation->jobSteps->map(function($step) {
                return [
                    'id' => $step->id,
                    'title' => $this->getLocalizedColumn($step, 'step_title'),
                ];
            }),
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
