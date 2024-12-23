<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Illuminate\Support\Carbon;

beforeEach(function () {
// Find the existing user
$this->user = User::where('email', 'zcrroll@gmail.com')->first();

// If user doesn't exist in test DB, create it
if (!$this->user) {
    $this->user = User::create([
        'id' => 135,
        'uuid' => 'bad46547-afbc-412b-b421-ea8df216c774',
        'name' => 'hamid chakkouri',
        'email' => 'zcrroll@gmail.com',
        'google_id' => '106700729275441873960',
        'password' => '$2y$12$QiInPF4r9sJ9jBCKuEs3suwuybKo8/RGtaFK1fyF4YAvro4wWAOdi',
        'remember_token' => 'RQtltbw2YyOo82iZeppR0lRiw5aey3ir4krwU9hoNU7j30HDsliJIrDJBw4d',
        'email_verified_at' => Carbon::parse('2024-11-20 10:59:08'),
        'created_at' => Carbon::parse('2024-11-20 10:59:08'),
        'updated_at' => Carbon::parse('2024-12-20 16:04:50')
    ]);
}
});

describe('Login Page', function() {
it('can render the login page', function() {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Auth/Login')
        ->has('canResetPassword')
        ->where('status', null)
    );
});

it('shows error message for invalid credentials', function() {
    $response = $this->post('/login', [
        'email' => 'wrong@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

it('redirects authenticated users away from login page', function() {
    $this->actingAs($this->user);
    
    $response = $this->get('/login');
    
    $response->assertRedirect('/dashboard');
});
});

describe('Google Authentication', function() {
beforeEach(function() {
    config(['app.url' => 'http://localhost:8000']);
    config(['services.google' => [
        'client_id' => 'test-client-id',
        'client_secret' => 'test-client-secret',
        'redirect' => 'http://localhost:8000/auth/google/callback'
    ]]);
});

it('redirects to Google OAuth', function() {
    $mockSocialite = Mockery::mock('Laravel\Socialite\Contracts\Factory');
    $mockProvider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
    
    $mockProvider->shouldReceive('redirect')
        ->once()
        ->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));
    
    $mockSocialite->shouldReceive('driver')
        ->with('google')
        ->andReturn($mockProvider);
    
    Socialite::swap($mockSocialite);
    
    $response = $this->get('/auth/google');
    
    $response->assertStatus(302);
    $this->assertStringContainsString('accounts.google.com', $response->getTargetUrl());
});

it('can handle Google callback for existing user', function() {
    $mockSocialite = Mockery::mock('Laravel\Socialite\Contracts\Factory');
    $mockProvider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
    
    $googleUser = (object)[
        'id' => '106700729275441873960',
        'name' => 'hamid chakkouri',
        'email' => 'zcrroll@gmail.com',
    ];
    
    $mockProvider->shouldReceive('user')->andReturn($googleUser);
    $mockSocialite->shouldReceive('driver')->with('google')->andReturn($mockProvider);
    
    Socialite::swap($mockSocialite);
    
    $response = $this->get('/auth/google/callback');
    
    $this->assertDatabaseHas('users', [
        'email' => 'zcrroll@gmail.com',
        'google_id' => '106700729275441873960',
        'name' => 'hamid chakkouri'
    ]);
    
    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($this->user->fresh());
});

it('handles Google authentication errors gracefully', function() {
    $mockSocialite = Mockery::mock('Laravel\Socialite\Contracts\Factory');
    $mockProvider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
    
    $mockProvider->shouldReceive('user')
        ->andThrow(new Exception('Google authentication failed'));
    $mockSocialite->shouldReceive('driver')
        ->with('google')
        ->andReturn($mockProvider);
    
    Socialite::swap($mockSocialite);
    
    $response = $this->get('/auth/google/callback');
    
    $response->assertRedirect('/login');
    $response->assertSessionHas('error', 'Google authentication failed. Please try again.');
});


});

describe('Login Form Validation', function() {
it('requires email', function() {
    $response = $this->post('/login', [
        'email' => '',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors(['email']);
});

it('requires valid email format', function() {
    $response = $this->post('/login', [
        'email' => 'invalid-email',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors(['email']);
});

it('requires password', function() {
    $response = $this->post('/login', [
        'email' => 'zcrroll@gmail.com',
        'password' => '',
    ]);

    $response->assertSessionHasErrors(['password']);
});

it('rate limits excessive login attempts', function() {
    foreach(range(1, 5) as $_) {
        $this->post('/login', [
            'email' => 'zcrroll@gmail.com',
            'password' => 'wrong-password',
        ]);
    }

    $response = $this->post('/login', [
        'email' => 'zcrroll@gmail.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(429);
});
}); 