<?php

namespace App\Http\Controllers\Filters;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DegreeFilterController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale();
        $nameColumn = $locale === 'fr' ? 'name_fr' : 'name';

        $query = Degree::query()
            ->select('id', $nameColumn, 'image', 'slug', 'salary');

        $filters = [];

        if ($search = $request->input('q')) {
            $filters['q'] = $search;
            $query->where(function ($query) use ($search, $nameColumn) {
                $query->where($nameColumn, 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($levels = $request->input('level')) {
            $filters['level'] = $levels;
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

        if ($request->has('has_skills')) {
            $query->whereHas('degreeSkills');
        }

        $degrees = $query->paginate(12);

        return Inertia::render('Degrees/Index', [
            'degrees' => Inertia::merge(function () use ($degrees, $nameColumn) {
                return [
                    'data' => $degrees->map(function ($degree) use ($nameColumn) {
                        return [
                            'id' => $degree->id,
                            'name' => $degree->$nameColumn,
                            'image' => $degree->image,
                            'slug' => $degree->slug,
                            'satisfaction' => $degree->satisfaction,
                        ];
                    }),
                    'meta' => [
                        'current_page' => $degrees->currentPage(),
                        'last_page' => $degrees->lastPage(),
                        'links' => $degrees->linkCollection()->toArray(),
                        'path' => $degrees->path(),
                        'per_page' => $degrees->perPage(),
                        'to' => $degrees->lastItem(),
                        'total' => $degrees->total(),
                    ],
                ];
            }),
            'filters' => $filters,
        ]);
    }

   
}