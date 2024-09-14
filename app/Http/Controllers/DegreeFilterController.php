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
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });

        $degrees = $query->paginate(12)->appends($request->all());
         ds($degrees->linkCollection()->toArray());
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
            'filters' => $request->only(['search'])
        ]);
    }

    public function filter(Request $request)
    {
        // This method is no longer needed as filtering is handled in the index method
    }
}