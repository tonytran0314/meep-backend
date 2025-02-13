<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\NotificationResource;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use HttpResponses;

    public function index() {
        $userId = Auth::user()->id;
        $notifications = Notification::where('receiver_id', $userId)->where('seen', false)->get();
        return $this->success(NotificationResource::collection($notifications));
    }

    public function seen(Request $request) {
        foreach($request->notifications as $notification) {
            if($notification['type'] !== 1) {
                $notificationRecord = Notification::find($notification['id']);
                $notificationRecord->seen = true;
                $notificationRecord->save();
            }
        }
    }
}
