<?php

use App\Http\Controllers\Api\v1\AuthenticationController;
use App\Http\Controllers\Api\v1\MessageController;
use App\Http\Controllers\Api\v1\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
    Route::middleware('auth:sanctum')->group(function() {
        Route::apiResource('/messages', MessageController::class)->only(['index', 'store']);
        Route::apiResource('/rooms', MessageController::class);
        Route::controller(ProfileController::class)->group(function() {
            Route::get('/profile', 'show');
            Route::put('/profile', 'update');
        });
    });

    Route::controller(AuthenticationController::class)->group(function() {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout');
        Route::post('/signup', 'signup');
    });
});