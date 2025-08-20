<?php

use App\Models\Department;
use App\Models\Diagnosis;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect(route('workspace'));
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/workspace', [\App\Http\Controllers\WorkspaceController::class, 'show'])->name('workspace');
    Route::post('/workspace', [\App\Http\Controllers\WorkspaceController::class, 'update'])->name('workspace.post');
    Route::post('/workspace/diagnosis', [\App\Http\Controllers\WorkspaceController::class, 'setDiagnosis'])->name('workspace.diagnosis.post');

    Route::post('/user/department/update', [\App\Http\Controllers\Api\v1\DepartmentController::class, 'update'])
        ->name('user.department.update');

    Route::get('/request', [\App\Http\Controllers\SurveyController::class, 'show'])
        ->name('request.create');

    Route::post('/request', [\App\Http\Controllers\SurveyController::class, 'store'])
        ->name('request.store');

    Route::delete('/request', [\App\Http\Controllers\SurveyController::class, 'deleteResult'])
        ->name('request.delete');

    Route::get('/request/result', [\App\Http\Controllers\SurveyController::class, 'result'])
        ->name('request.result');

    Route::prefix('patient')->group(function () {
        Route::prefix('{patientResult}')->group(function () {
            Route::get('/', [\App\Http\Controllers\PatientResultController::class, 'show'])
                ->name('patientResult.show');
            Route::put('/', [\App\Http\Controllers\PatientResultController::class, 'update'])
                ->name('patientResult.update');
        });
    });

    Route::prefix('my')->group(function () {
        Route::get('/requests', [\App\Http\Controllers\MyController::class, 'requests'])
            ->name('my.request');

        Route::post('/requests/update', [\App\Http\Controllers\MyController::class, 'update'])
            ->name('my.request.update');
    });

    Route::prefix('ai')->group(function () {
        Route::get('/generate', [\App\Http\Controllers\Ai\GigaChatController::class, 'generate']);
        Route::get('/resume', [\App\Http\Controllers\Ai\GigaChatController::class, 'resumePatient'])->name('ai.resume');
    });

    Route::prefix('calculate')->group(function () {
        Route::prefix('evacuation')->group(function () {
            Route::get('/route', [\App\Http\Controllers\CalculateController::class, 'calculateEvacuation']);
        });
    });
});
