<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::prefix('departments')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\v1\DepartmentController::class, 'index']);
        Route::post('/search', [\App\Http\Controllers\Api\v1\DepartmentController::class, 'search']);
    });

    Route::prefix('requests')->group(function () {
       Route::prefix('statuses')->group(function () {
           Route::get('/', [\App\Http\Controllers\RequestController::class, 'statuses'])
               ->name('api.requests.statuses');
       });
    });

    Route::prefix('user')->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\v1\AuthController::class, 'user']);
            Route::post('/logout', [\App\Http\Controllers\Api\v1\AuthController::class, 'logout']);
        });
    });

    Route::prefix('auth')->group(function () {
        Route::post('/login', [\App\Http\Controllers\Api\v1\AuthController::class, 'login']);
    });

    Route::prefix('my')->group(function () {
        Route::middleware([
            'auth:sanctum',
            'web',
            config('jetstream.auth_session'),
        ])->get('requests', [\App\Http\Controllers\Api\v1\RequestController::class, 'index'])->name('api.my.requests');
    });
});
