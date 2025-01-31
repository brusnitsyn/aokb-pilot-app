<?php

use App\Models\Department;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/workspace', function () {
    return Inertia::render('Workspace', [
        'departments' => Department::all(),
    ]);
})->name('workspace');

Route::get('/request', function () {
    return Inertia::render('Request/Create');
})->name('request.create');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

});
