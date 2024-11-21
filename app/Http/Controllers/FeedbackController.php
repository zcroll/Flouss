<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string|max:1000',
        ]);

        $feedback = Feedback::create([
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'feedback' => $validated['feedback'],
        ]);
        ds($feedback);

        return back()->with('message', 'Thank you for your feedback!');
    }
}
