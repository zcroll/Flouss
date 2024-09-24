<?php

use function Pest\Laravel\get;
use Inertia\Testing\AssertableInertia;

it('contains languegegloval data', function () {
    $response = get('/')
    ->assertInertia(function (AssertableInertia $page) {
        $page->has('lang');
    });

});
