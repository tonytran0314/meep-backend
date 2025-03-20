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

    private $room;
    private $userIdThatReceiveThisEvent;

    /**
     * Create a new event instance.
     */
    public function __construct($room, $userIdThatReceiveThisEvent)
    {
        $this->room = $room;
        $this->userIdThatReceiveThisEvent = $userIdThatReceiveThisEvent;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('new-room.' . $this->userIdThatReceiveThisEvent),
        ];
    }

    public function broadcastAs()
    {
        return 'NewRoom';
    }

    public function broadcastWith(): array
    {
        $otherUser = $this->room->users->where('id', '!=', $this->userIdThatReceiveThisEvent)->first();

        return [
            'room' => [
                'id' => $this->room->id,
                'avatar' => asset('storage/' . $otherUser->avatar),
                'isGroup' => false,
                'name' => $otherUser->name,
                'latestMessage' => null
            ]
        ];
    
    }
}
