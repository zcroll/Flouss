<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DegreeFilterController extends Controller
{
    public function index()
    {
        return Inertia::render('Degrees/Index', [
            'degrees' => Degree::all(),
        ]);
    }

    public function filter(Request $request)
    {
        $query = Degree::query();

        // Add filter logic here based on request parameters
        // For example:
        if ($request->has('level')) {
            $query->where('level', $request->level);
        }

        if ($request->has('field')) {
            $query->where('field', 'like', '%' . $request->field . '%');
        }

        // Add more filters as needed

        $degrees = $query->get();

        return response()->json($degrees);
    }
}