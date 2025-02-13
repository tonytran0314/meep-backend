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

class NewChatRoom implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $senderId;
    public $receiverId;

    /**
     * Create a new event instance.
     */
    public function __construct($room, $senderId, $receiverId)
    {
        $this->room = $room;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('new-room.' . $this->receiverId),
            new PrivateChannel('new-room.' . $this->senderId)
        ];
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
