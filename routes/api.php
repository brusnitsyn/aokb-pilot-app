<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('/departments', [\App\Http\Controllers\Api\v1\DepartmentController::class, 'index']);
    Route::middleware([\Illuminate\Session\Middleware\StartSession::class, \App\Http\Middleware\HandleInertiaRequests::class])->group(function () {
        Route::prefix('user')->group(function () {
            Route::post('/department', [\App\Http\Controllers\Api\v1\DepartmentController::class, 'userStore']);
        });
    });
});
