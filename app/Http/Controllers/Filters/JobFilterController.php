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
        $query = JobInfo::query();

        $filters = [];

        if ($search = $request->input('q')) {
            $filters['q'] = $search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%");
        }
        // dd($filters);
        if ($levels = $request->input('education')) {
            $filters['education'] = $levels;
            $query->whereIn('eucation_level', $levels);
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
                'data' => $jobs->items(),
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