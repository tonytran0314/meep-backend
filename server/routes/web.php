<?php

use App\Events\v1\SendMessage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    broadcast(new SendMessage('here we go'));

    return view('welcome');
});
