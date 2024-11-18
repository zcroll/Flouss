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
use Inertia\Inertia;

class GoogleController extends Controller
{
    protected function getRedirectUrl()
    {
        $appUrl = config('app.url');
        $isProduction = $appUrl === 'https://gennz.site';
        
        return $isProduction 
            ? 'https://gennz.site/auth/google/callback'
            : 'http://localhost:8000/auth/google/callback';
    }

    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')
                ->redirectUrl($this->getRedirectUrl())
                ->redirect();
        } catch (Exception $e) {
            Log::error('Google OAuth error: ' . $e->getMessage());
            return to_route('login')->with('error', 'Could not connect to Google. Please try again.');
        }
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl($this->getRedirectUrl())
                ->user();
            
            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(Str::random(24)),
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user);
            
            $request->session()->regenerate();
            
            return to_route('dashboard');
            
        } catch (Exception $e) {
            Log::error('Google auth error: ' . $e->getMessage());
            return to_route('login')->with('error', 'Google authentication failed. Please try again.');
        }
    }
}
