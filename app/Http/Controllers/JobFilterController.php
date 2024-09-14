<?php

namespace App\Http\Controllers;

use App\Models\JobInfo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobFilterController extends Controller
{
    public function index(Request $request)
    {
        $query = JobInfo::query()
            ->when($request->input('q'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->when($request->input('education'), function ($query, $levels) {
                $query->where(function ($q) use ($levels) {
                    foreach ($levels as $level) {
                        $q->orWhereRaw('FIND_IN_SET(?, education_level)', [$level]);
                    }
                });
            })
            ->when($request->input('sort'), function ($query, $sort) {
                switch ($sort) {
                    case 'salary_desc':
                        $query->orderBy('salary', 'desc');
                        break;
                    case 'satisfaction_desc':
                        $query->orderBy('satisfaction', 'desc');
                        break;
                }
            });

        $jobs = $query->paginate(12)->appends($request->all());

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
            'filters' => $request->only(['q', 'education', 'sort'])
        ]);
    }
}