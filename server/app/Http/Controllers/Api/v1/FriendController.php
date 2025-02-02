<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\User;
use App\Models\PendingFriend;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    use HttpResponses;

    /* -------------------------------------------------------------------------- */
    /*                               Search friends                               */
    /* -------------------------------------------------------------------------- */
    public function search(Request $request) {
        $username = $request->query('username');
        $myself = Auth::user();

        // Lấy danh sách receiver_id từ các yêu cầu kết bạn do mình gửi đi
        $pendingFriendIds = PendingFriend::where('sender_id', $myself->id)
                                        ->pluck('receiver_id')
                                        ->toArray();

        // Truy vấn danh sách người dùng, loại bỏ bản thân và những người đang chờ xác nhận kết bạn
        $friends = User::where('username', 'LIKE', "%$username%")
                        ->where('id', '!=', $myself->id)
                        ->whereNotIn('id', $pendingFriendIds)
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

        // Broadcast the add friend request to the receiver as notification
        
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
    public function reject() {

    }

    /* -------------------------------------------------------------------------- */
    /*                    Remove a friend (delete a chat room)                    */
    /* -------------------------------------------------------------------------- */
    public function remove(){

    }
}
