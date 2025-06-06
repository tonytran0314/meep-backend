<?php

use App\Http\Controllers\Api\v1\AuthenticationController;
use App\Http\Controllers\Api\v1\MessageController;
use App\Http\Controllers\Api\v1\RoomController;
use App\Http\Controllers\Api\v1\ProfileController;
use App\Http\Controllers\Api\v1\FriendController;
use App\Http\Controllers\Api\v1\GroupController;
use App\Http\Controllers\Api\v1\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
    Route::middleware('auth:sanctum')->group(function() {
        Route::apiResource('/messages', MessageController::class)->only(['index', 'store']);
        Route::apiResource('/rooms', RoomController::class);
        Route::apiResource('/groups', GroupController::class)->only(['store', 'update']);
        Route::controller(ProfileController::class)->group(function() {
            Route::get('/profile', 'show');
            Route::put('/profile', 'update');
        });
        Route::controller(FriendController::class)->group(function() {
            Route::get('/list-friends', 'index');
            Route::get('/search-friends', 'search');
            Route::post('/add-friend', 'add');
            Route::post('/accept-friend', 'accept');
            Route::post('/reject-friend', 'reject');
            Route::post('/remove-friend', 'remove');
        });
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/seen-notifications', [NotificationController::class, 'seen']);
    });

    Route::controller(AuthenticationController::class)->group(function() {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout');
        Route::post('/signup', 'signup');
    });
});