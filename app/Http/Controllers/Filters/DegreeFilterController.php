<?php

namespace App\Http\Controllers\Filters;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use App\Models\JobInfo;
use App\Models\Question;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DegreeFilterController extends Controller
{
    public function index(Request $request)
    {
        $query = Degree::query();


        $user = \DB::table('job_infos')->get();
        ds($user);

        $filters = [];

        if ($search = $request->input('q')) {
            $filters['q'] = $search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%");
        }

        if ($levels = $request->input('education')) {
            $filters['education'] = $levels;
            $query->where(function ($q) use ($levels) {
                foreach ($levels as $level) {
                    $q->orWhereRaw('FIND_IN_SET(?, education_levels)', [$level]);
                }
            });
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

        if ($industries = $request->input('industries')) {
            $filters['industries'] = $industries;
            $query->whereHas('industries', function ($q) use ($industries) {
                $q->whereIn('id', $industries);
            });
        }

        $degrees = $query->paginate(12)->appends($filters);

        ds($degrees);

        return Inertia::render('Degrees/Index', [
            'degrees' => [
                'data' => $degrees->items(),
                'links' => $degrees->linkCollection()->toArray(),
                'meta' => [
                    'current_page' => $degrees->currentPage(),
                    'from' => $degrees->firstItem(),
                    'last_page' => $degrees->lastPage(),
                    'links' => $degrees->linkCollection()->toArray(),
                    'path' => $degrees->path(),
                    'per_page' => $degrees->perPage(),
                    'to' => $degrees->lastItem(),
                    'total' => $degrees->total(),
                ],
            ],
            'filters' => $filters
        ]);
    }

    public function filter(Request $request)
    {
        // This method is no longer needed as filtering is handled in the index method
    }
}