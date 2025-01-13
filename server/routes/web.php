<?php

use App\Events\v1\SendMessage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
