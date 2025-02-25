<?php

namespace App\Events\v1;

use App\Http\Resources\v1\RoomResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewGroupChat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $userIds;

    public function __construct($room, $userIds)
    {
        $this->room = $room;
        $this->userIds = $userIds;
    }

    public function broadcastOn()
    {
        // Broadcast to multiple private channels (one for each user)
        return collect($this->userIds)->map(fn($id) => new PrivateChannel("new-room.{$id}"))->toArray();
    }

    public function broadcastAs()
    {
        return 'NewRoom';
    }

    public function broadcastWith(): array
    {
        return [
            'room' => new RoomResource($this->room)
        ];
    
    }
}
