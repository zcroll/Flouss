<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});

// Home > Jobs
Breadcrumbs::for('jobs.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Jobs', route('jobs.index'));
});

// Home > Jobs > [Job Name]
Breadcrumbs::for('career', function (BreadcrumbTrail $trail, $job) {
    $trail->parent('jobs.index');
    $trail->push($job['name'], route('career', $job['slug']));
});

// Home > Jobs > [Job Name] > Personality
Breadcrumbs::for('career.personality', function (BreadcrumbTrail $trail, $job) {
    $trail->parent('career', $job);
    $trail->push('Personality', route('career.personality', $job['slug']));
}); 