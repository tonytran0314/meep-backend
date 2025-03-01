<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\v1\RoomResource;

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
        }

        return $this->success([
            'name' => $name,
            'isGroup' => $room->is_group,
            'messages' => $messages
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
