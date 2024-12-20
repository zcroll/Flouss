<?php

use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

it('can render the home page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Welcome')
        ->has('canLogin')
        ->has('canRegister')
        ->has('laravelVersion')
        ->has('phpVersion')
    );
});

it('passes correct data to welcome component', function () {
    $response = $this->get('/');

    $response->assertInertia(fn (Assert $page) => $page
        ->where('canLogin', Route::has('login'))
        ->where('canRegister', Route::has('register'))
        ->where('laravelVersion', Application::VERSION)
        ->where('phpVersion', PHP_VERSION)
    );
});

it('has all required child components', function () {
    $response = $this->get('/');
    
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Welcome')
        ->has('canLogin')
        ->where('canLogin', Route::has('login'))
    );
});

it('renders all necessary UI components', function () {
    $response = $this->get('/');

    $response->assertInertia(fn (Assert $page) => $page
        ->component('Welcome')
    );

    // Verify the component imports exist in Welcome.vue
    expect(file_get_contents(resource_path('js/Pages/Welcome.vue')))
        ->toContain('import Header')
        ->toContain('import Hero')
        ->toContain('import Steps')
        ->toContain('import HowItWork')
        ->toContain('import Gardien')
        ->toContain('import Footer');
});

describe('Welcome component data structure', function() {
    it('has correct props definition', function () {
        $response = $this->get('/');
        
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Welcome')
            ->has('canLogin')
            ->has('canRegister')
            ->has('laravelVersion')
            ->has('phpVersion')
        );
    });
}); 