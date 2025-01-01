<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('dashboard'));
});

// Career routes
Breadcrumbs::for('career', function (BreadcrumbTrail $trail, $career) {
    $trail->parent('home');
    $trail->push($career->name, route('career', $career->slug));
});

Breadcrumbs::for('career.workEnvironments', function (BreadcrumbTrail $trail, $career) {
    $trail->parent('career', $career);
    $trail->push('Work Environments', route('career.workEnvironments', $career->slug));
});

Breadcrumbs::for('career.personality', function (BreadcrumbTrail $trail, $career) {
    $trail->parent('career', $career);
    $trail->push('Personality', route('career.personality', $career->slug));
});

Breadcrumbs::for('career.how-to-become', function (BreadcrumbTrail $trail, $career) {
    $trail->parent('career', $career);
    $trail->push('How to Become', route('career.how-to-become', $career->slug));
});

// Degree routes
Breadcrumbs::for('degrees', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Degrees', route('degrees.index'));
});

Breadcrumbs::for('degree.index', function (BreadcrumbTrail $trail, $degree) {
    $trail->parent('degrees');
    $trail->push($degree->name, route('degree.index', $degree->slug));
});

Breadcrumbs::for('degree.skills', function (BreadcrumbTrail $trail, $degree) {
    $trail->parent('degree.index', $degree);
    $trail->push('Skills', route('degree.skills', $degree->slug));
});

Breadcrumbs::for('degree.jobs', function (BreadcrumbTrail $trail, $degree) {
    $trail->parent('degree.index', $degree);
    $trail->push('Jobs', route('degree.jobs', $degree->slug));
});

Breadcrumbs::for('degree.howToObtain', function (BreadcrumbTrail $trail, $degree) {
    $trail->parent('degree.index', $degree);
    $trail->push('How to Obtain', route('degree.howToObtain', $degree->slug));
});

// Jobs routes
Breadcrumbs::for('jobs', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Jobs', route('jobs.index'));
});

Breadcrumbs::for('job.show', function (BreadcrumbTrail $trail, $job) {
    $trail->parent('jobs');
    $trail->push($job->name, route('job.show', $job));
}); 