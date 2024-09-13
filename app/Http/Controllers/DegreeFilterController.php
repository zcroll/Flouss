<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DegreeFilterController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Degrees/Index', [
            'degrees' => Degree::query()
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                })
                ->paginate(12)
                ->withQueryString(),
            'filters' => $request->only(['search'])
        ]);
    }

    public function filter(Request $request)
    {
        // This method is no longer needed as filtering is handled in the index method
    }
}