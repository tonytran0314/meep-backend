<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\v1\NewNotification;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\User;
use App\Models\PendingFriend;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    use HttpResponses;

    /* -------------------------------------------------------------------------- */
    /*                               Search friends                               */
    /* -------------------------------------------------------------------------- */
    public function search(Request $request) {
        $username = $request->query('username');
        $myself = Auth::user();

        $pendingSenders = PendingFriend::pluck('sender_id')->unique()->toArray();
        $pendingReceivers = PendingFriend::whereIn('sender_id', $pendingSenders)
                                         ->orWhereIn('receiver_id', $pendingSenders)
                                         ->pluck('receiver_id')
                                         ->merge($pendingSenders)
                                         ->unique()
                                         ->toArray();
    
        $roomIds = DB::table('room_user')
                    ->join('rooms', 'rooms.id', '=', 'room_user.room_id')
                    ->where('room_user.user_id', $myself->id)
                    ->where('rooms.is_group', false)
                    ->pluck('room_user.room_id');
    
        $chatFriends = DB::table('room_user')
                         ->whereIn('room_id', $roomIds)
                         ->where('user_id', '!=', $myself->id)
                         ->pluck('user_id')
                         ->toArray();
    
        $friends = User::where('username', 'LIKE', "%$username%")
                       ->where('id', '!=', $myself->id)
                       ->whereNotIn('id', $pendingReceivers)
                       ->whereNotIn('id', $chatFriends)
                       ->get();
    
        return $this->success($friends);
    }
    
    

    /* -------------------------------------------------------------------------- */
    /*                           Send add friend request                          */
    /* -------------------------------------------------------------------------- */
    public function add(Request $request) {
        $senderId = Auth::user()->id;
        $receiverId = (int) $request->userId;

        $exists = PendingFriend::where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->exists();

        if ($exists) {
            return $this->error(null, 'Friend request already sent', 400);
        }

        PendingFriend::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
        ]);

        $notification = Notification::create([
            'seen' => false,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'type' => 1
        ]);

        // Broadcast the add friend request to the receiver as notification
        broadcast(new NewNotification($notification));
        
        return $this->success(null);
    }

    /* -------------------------------------------------------------------------- */
    /*                          Accept add friend request                         */
    /* -------------------------------------------------------------------------- */
    public function accept() {

    }

    /* -------------------------------------------------------------------------- */
    /*                          Reject add friend request                         */
    /* -------------------------------------------------------------------------- */
    public function reject(Request $request) {
        $senderId = (int) $request->senderId;
        $receiverId = (int) $request->receiverId;

        PendingFriend::where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId)
                    ->delete();

        Notification::where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId)
                    ->delete();

        $message = 'Removed: sender id = ' . $senderId . ' with receiver id = ' . $receiverId;
        return $this->success($message);
    }

    /* -------------------------------------------------------------------------- */
    /*                    Remove a friend (delete a chat room)                    */
    /* -------------------------------------------------------------------------- */
    public function remove(){

    }
}
