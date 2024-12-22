<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    protected function getRedirectUrl()
    {
        return config('app.url') . '/auth/google/callback';
    }

    public function redirectToGoogle()
    {
        try {
            config(['services.google.redirect' => $this->getRedirectUrl()]);
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
            Log::error('Google OAuth redirect error: ' . $e->getMessage());
            return to_route('login')->with('error', 'Could not connect to Google. Please try again.');
        }
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            config(['services.google.redirect' => $this->getRedirectUrl()]);
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                [
                    'email' => $googleUser->email,
                ],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(Str::random(24)),
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user, true); // Remember the user
            
            $request->session()->regenerate();
            
            return to_route('dashboard');
            
        } catch (Exception $e) {
            Log::error('Google auth callback error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            return to_route('login')->with('error', 'Google authentication failed. Please try again.');
        }
    }
}
