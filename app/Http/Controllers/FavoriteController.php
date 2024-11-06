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

        $favorite = Favorite::create([
            'user_id' => auth()->id(),
            'favoritable_id' => $request->favoritable_id,
            'favoritable_type' => $type,
        ]);

        \Log::info('Favorite created', [
            'user_id' => $favorite->user_id,
            'favoritable_id' => $favorite->favoritable_id,
            'favoritable_type' => $favorite->favoritable_type
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
   
} 