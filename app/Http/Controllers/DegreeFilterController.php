<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DegreeFilterController extends Controller
{
    public function index(Request $request)
    {
        $query = Degree::query()
            ->when($request->input('q'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->when($request->input('education'), function ($query, $levels) {
                $query->where(function ($q) use ($levels) {
                    foreach ($levels as $level) {
                        $q->orWhereRaw('FIND_IN_SET(?, education_levels)', [$level]);
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
            })
            ->when($request->input('industries'), function ($query, $industries) {
                $query->whereHas('industries', function ($q) use ($industries) {
                    $q->whereIn('id', $industries);
                });
            });

        $degrees = $query->paginate(12)->appends($request->all());

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
            'filters' => $request->only(['q', 'education', 'sort', 'industries'])
        ]);
    }

    public function filter(Request $request)
    {
        // This method is no longer needed as filtering is handled in the index method
    }
}