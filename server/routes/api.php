<?php

use App\Http\Controllers\Api\v1\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
    Route::post('/send-message', [MessageController::class, 'sendMessage']);
});