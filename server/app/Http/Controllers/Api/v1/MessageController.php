<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\v1\SendMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\StoreMessageRequest;
use App\Models\Message;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    use HttpResponses;

    public function index() {
        return $this->success(null);
    }

    public function store(StoreMessageRequest $request) {
        try {
            $message = Message::create([
                'room_id' => (int) $request->room_id,
                'user_id' => Auth::user()->id,
                'content' => $request->content
            ]);

            broadcast(new SendMessage($message));
    
            return $this->success(null);

        } catch (Exception $error) {
            return $this->error(null, 'Failed to send message', 500);
        }
    }
}
