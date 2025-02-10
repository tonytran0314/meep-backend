<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\NotificationResource;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    use HttpResponses;

    public function index() {
        $userId = Auth::user()->id;
        $notifications = Notification::where('receiver_id', $userId)->get();
        return $this->success(NotificationResource::collection($notifications));
    }
}
