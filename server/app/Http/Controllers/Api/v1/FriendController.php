<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\v1\NewChatRoom;
use App\Events\v1\NewNotification;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\User;
use App\Models\Friend;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    use HttpResponses;

    /* -------------------------------------------------------------------------- */
    /*                                 Friend list                                */
    /* -------------------------------------------------------------------------- */
    public function index() {
        $userId = Auth::user()->id;

        $friendIds = Friend::where(function ($query) use ($userId) {
                        $query->where('sender_id', $userId);
                        $query->orWhere('receiver_id', $userId);
                    }) 
                    ->where('status', 'accepted')
                    ->get()
                    ->map(function ($record) use ($userId) {
                        return $record->sender_id === $userId ? $record->receiver_id : $record->sender_id;
                    })
                    ->unique()
                    ->values();
        
        $friends = User::whereIn('id', $friendIds)->get();

        return $this->success($friends);
    }



    /* -------------------------------------------------------------------------- */
    /*                               Search friends                               */
    /* -------------------------------------------------------------------------- */
    public function search(Request $request) {
        $username = $request->query('username');
        $myself = Auth::user();

        $pendingSenders = Friend::pluck('sender_id')->unique()->toArray();
        $pendingReceivers = Friend::whereIn('sender_id', $pendingSenders)
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

        $exists = Friend::where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->exists();

        if ($exists) {
            return $this->error(null, 'Friend request already sent', 400);
        }

        Friend::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'status' => 'pending'
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
    public function accept(Request $request) {
        $senderId = (int) $request->senderId;
        $receiverId = (int) $request->receiverId;

        $this->friendStatusFromPendingToAccepted($senderId, $receiverId);
        $this->deleteNotification($senderId, $receiverId);

        $newRoom = $this->createNewRoom();

        $this->assignUsersToNewRoom($newRoom->id, $senderId, $receiverId);

        // this function can only create a new notification with type = 2 now. try to make it flexible
        $newNotification = $this->createNewNotification($senderId, $receiverId);

        broadcast(new NewChatRoom($newRoom, $senderId)); // for the person who sent the friend request
        broadcast(new NewChatRoom($newRoom, $receiverId)); // for the person who received the friend request

        // this notification would be sent to the person, who sent the add friend request
        // say that "User abd accepted your add friend request"
        broadcast(new NewNotification($newNotification));

        return $this->success('Accepted an add friend request');
    }

    /* -------------------------------------------------------------------------- */
    /*                          Reject add friend request                         */
    /* -------------------------------------------------------------------------- */
    public function reject(Request $request) {
        $senderId = (int) $request->senderId;
        $receiverId = (int) $request->receiverId;

        $this->deletePendingFriend($senderId, $receiverId);
        $this->deleteNotification($senderId, $receiverId);

        return $this->success('Rejected an add friend request');
    }

    /* -------------------------------------------------------------------------- */
    /*                    Remove a friend (delete a chat room)                    */
    /* -------------------------------------------------------------------------- */
    public function remove(){

    }


    private function createNewRoom() {
        $newRoom = Room::create([
            'avatar' => null,
            'is_group' => 0
        ]);

        return $newRoom;
    }

    private function assignUsersToNewRoom($roomId, $senderId, $receiverId) {
        DB::table('room_user')->insert([
            [
                'room_id' => $roomId,
                'user_id' => $senderId
            ],
            [
                'room_id' => $roomId,
                'user_id' => $receiverId
            ]
        ]);
    }

    private function deleteNotification($senderId, $receiverId) {
        Notification::where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId)
                    ->delete();
    }

    private function deletePendingFriend($senderId, $receiverId) {
        Friend::where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId)
                    ->delete();
    }

    private function friendStatusFromPendingToAccepted($senderId, $receiverId) {
        $friend = Friend::where('sender_id', $senderId)
                        ->where('receiver_id', $receiverId)
                        ->where('status', 'pending')
                        ->first();
        
        if ($friend) {
            $friend->status = 'accepted';
            $friend->save();
        }
    }

    private function createNewNotification($senderId, $receiverId) {
        $notification = Notification::create([
            'seen' => false,
            'sender_id' => $receiverId,
            'receiver_id' => $senderId,
            'type' => 2
        ]);

        return $notification;
    }
}
