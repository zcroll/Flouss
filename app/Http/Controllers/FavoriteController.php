<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\JobInfo;
use App\Models\Degree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'model_id' => 'required|integer',
            'model_type' => ['required', Rule::in(['career', 'degree'])]
        ]);

        $type = $request->model_type === 'career' ? JobInfo::class : Degree::class;

        // Find existing favorite
        $favorite = Favorite::where([
            'user_id' => Auth::id(),
            'favoritable_id' => $request->model_id,
            'favoritable_type' => $type,
        ])->first();

        // If favorite exists, remove it
        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Removed from favorites');
        }

        // Verify the model exists
        $model = $type::find($request->model_id);
        if (!$model) {
            abort(404, 'Model not found');
        }

        // Create new favorite
        Favorite::create([
            'user_id' => Auth::id(),
            'favoritable_id' => $request->model_id,
            'favoritable_type' => $type,
        ]);

        return back()->with('success', 'Added to favorites');
    }

    public function destroy($type, $id)
    {
        \Log::info('Destroy favorite request:', [
            'type' => $type,
            'id' => $id,
            'user' => Auth::id()
        ]);

        abort_unless(in_array($type, ['career', 'degree']), 404, 'Invalid type');

        $modelType = $type === 'career' ? JobInfo::class : Degree::class;

        // Find and delete the favorite
        $favorite = Favorite::where([
            'user_id' => Auth::id(),
            'favoritable_id' => $id,
            'favoritable_type' => $modelType,
        ])->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Removed from favorites');
        }

        return back()->with('error', 'Favorite not found');
    }
}
