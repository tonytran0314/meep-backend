<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\v1\SendMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage(Request $request) {

        broadcast(new SendMessage($request->content));

        return response()->json([
            'status' => 'OK' 
        ]);
    }
}
