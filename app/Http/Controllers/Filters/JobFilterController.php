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
        $descriptionColumn = $locale === 'fr' ? 'description_fr' : 'description';

        $query = JobInfo::query();
        $query->orderBy('tranche', 'asc');

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

        $jobs = $query->paginate(12)->appends($filters);

        return Inertia::render('Jobs/Index', [
            'jobs' => [
                'data' => $jobs->map(function ($job) use ($locale, $nameColumn, $descriptionColumn) {
                    return [
                        'id' => $job->id,
                        'name' => $job->$nameColumn,
                        'image'=>$job->image,
                        'slug' => $job->slug,
                        'description' => $job->$descriptionColumn,
                        'salary' => $job->salary,
                        'satisfaction' => $job->satisfaction,
                    ];
                }),
                'links' => $jobs->linkCollection()->toArray(),
                'meta' => [
                    'current_page' => $jobs->currentPage(),
                    'from' => $jobs->firstItem(),
                    'last_page' => $jobs->lastPage(),
                    'links' => $jobs->linkCollection()->toArray(),
                    'path' => $jobs->path(),
                    'per_page' => $jobs->perPage(),
                    'to' => $jobs->lastItem(),
                    'total' => $jobs->total(),
                ],
            ],
            'filters' => $filters
        ]);
    }
}