<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Degree;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'favoritable_id' => 'required|integer',
            'favoritable_type' => 'required|in:career,degree'
        ]);

        // Convert favoritable_type to model class name
        $type = $request->favoritable_type === 'career' ? 'App\Models\JobInfo' : 'App\Models\Degree';

        $favorite = Favorite::where([
            'user_id' => auth()->id(),
            'favoritable_id' => $request->favoritable_id,
            'favoritable_type' => $type,
        ])->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Removed from favorites');
        }

        Favorite::create([
            'user_id' => auth()->id(),
            'favoritable_id' => $request->favoritable_id,
            'favoritable_type' => $type,
        ]);

        return back()->with('success', 'Added to favorites');
    }

    public function destroy(Favorite $favorite)
    {
        if ($favorite->user_id !== auth()->id()) {
            abort(403);
        }

        $favorite->delete();
        
        return back()->with('success', 'Removed from favorites');
    }

    // Test method to verify isFavorite() logic
    public function test()
    {
        $degree = Degree::find(2);
        $isFavorite = $degree->isFavorite();
        ds($isFavorite);
        
        ds([
            'degree_id' => $degree->id,
            'is_favorite' => $isFavorite,
            'user_id' => auth()->id(),
            'favorites_count' => $degree->favorites()->count()
        ]);
    }
} 