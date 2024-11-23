<?php

namespace App\Http\Controllers\Filters;

use App\Http\Controllers\Controller;
use App\Models\JobInfo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobFilterController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale();
        $nameColumn = $locale === 'fr' ? 'name_fr' : 'name';

        $query = JobInfo::query()
            ->select('id', 'name', 'name_fr', 'image', 'slug', 'salary', 'satisfaction')
            ->with(['jobInfoTypes' => function($query) use ($locale) {
                $query->select('id', $locale === 'fr' ? 'type_description_fr' : 'type_description');
            }])
            ->with(['jobInfoDetail' => function($query) use ($locale) {
                $query->select('id', 'job_info_id', $locale === 'fr' ? 'role_description_main_fr' : 'role_description_main');
            }])
            ->when($locale === 'fr', function ($query) {
                return $query->whereNotNull('name_fr');
            }, function ($query) {
                return $query->whereNotNull('name');
            });

        $query->orderBy('id', 'asc');

        $filters = [];

        if ($search = $request->input('q')) {
            $filters['q'] = $search;
            $query->where(function ($query) use ($search, $nameColumn) {
                $query->where($nameColumn, 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($levels = $request->input('education')) {
            $filters['education'] = $levels;
            $query->whereIn('education_level', $levels);
        }

        if ($sort = $request->input('sort')) {
            $filters['sort'] = $sort;
            switch ($sort) {
                case 'salary_desc':
                    $query->orderBy('salary', 'desc');
                    break;
                case 'satisfaction_desc':
                    $query->orderBy('satisfaction', 'desc');
                    break;
            }
        }

        $jobs = $query->paginate(12);

        return Inertia::render('Jobs/Index', [
            'jobs' => Inertia::merge(function () use ($jobs, $nameColumn, $locale) {
                return [
                    'data' => $jobs->map(function ($job) use ($nameColumn, $locale) {
                        return [
                            'id' => $job->id,
                            'name' => $job->$nameColumn,
                            'image' => $job->image,
                            'slug' => $job->slug,
                            'salary' => $job->salary,
                            'satisfaction' => $job->satisfaction,
                            'type_descriptions' => $job->jobInfoTypes->pluck(
                                $locale === 'fr' ? 'type_description_fr' : 'type_description'
                            )->unique()->values(),
                            'description' => $job->jobInfoDetail->first()?->{$locale === 'fr' ? 'role_description_main_fr' : 'role_description_main'}
                        ];
                    }),
                    'meta' => [
                        'current_page' => $jobs->currentPage(),
                        'last_page' => $jobs->lastPage(),
                        'links' => $jobs->linkCollection()->toArray(),
                        'path' => $jobs->path(),
                        'per_page' => $jobs->perPage(),
                        'to' => $jobs->lastItem(),
                        'total' => $jobs->total(),
                    ],
                ];
            }),
            'filters' => $filters
            
        ]);
        
    }
}
