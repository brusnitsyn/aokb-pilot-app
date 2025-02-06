<?php

use App\Models\Department;
use App\Models\Diagnosis;
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

Route::get('/workspace', [\App\Http\Controllers\WorkspaceController::class, 'show'])->name('workspace');
Route::post('/workspace', [\App\Http\Controllers\WorkspaceController::class, 'update'])->name('workspace.post');

Route::post('/user/department/update', [\App\Http\Controllers\Api\v1\DepartmentController::class, 'update'])
    ->name('user.department.update');

Route::get('/request', [\App\Http\Controllers\SurveyController::class, 'show'])->middleware(\App\Http\Middleware\CheckSelectedDepartment::class)
    ->name('request.create');

Route::post('/request', [\App\Http\Controllers\SurveyController::class, 'store'])->middleware(\App\Http\Middleware\CheckSelectedDepartment::class)
    ->name('request.store');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

});
