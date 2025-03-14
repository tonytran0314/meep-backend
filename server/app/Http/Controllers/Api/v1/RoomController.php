<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\RoomResource;
use App\Http\Resources\v1\UserResource;

class RoomController extends Controller
{
    use HttpResponses;

    /**
     * List rooms of the current user
     */
    public function index()
    {
        $user = Auth::user();
        $rooms = $user->rooms()->with(['messages' => function ($query) {
                                    $query->latest()->limit(1); // Get only the latest message
                                }])->get();
        return $this->success(RoomResource::collection($rooms));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $roomId)
    {
        $user = Auth::user();
        $members = null;

        $room = $user->rooms()->where('rooms.id', $roomId)->first();

        if (!$room) {
            return $this->error(null, 'Unauthorized', 403);
        }

        $messages = $room->messages()->orderBy('created_at', 'desc')->get();

        $name = $room->name;

        if(!$room->is_group) {
            $room = Room::find($roomId);
            $otherUser = $room->users->where('id', '!=', $user->id)->first();

            $name = $otherUser ? $otherUser->name : null;
            $avatar = $otherUser ? asset('storage/' . $otherUser->avatar) : null; 
        } 
        
        if($room->is_group) {
            $room = Room::find($roomId);
            $members = UserResource::collection($room->users);
            $avatar = ($room->avatar !== null) ? asset('storage/' . $room->avatar) : null;
        }

        return $this->success([
            'id' => $room->id,
            'name' => $name,
            'isGroup' => $room->is_group,
            'members' => $members,
            'messages' => $messages,
            'avatar' => $avatar
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
