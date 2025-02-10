<?php

namespace App\Events\v1;

use App\Http\Resources\v1\NotificationResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    /**
     * Create a new event instance.
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('notification.' . $this->notification->receiver_id)
        ];
    }

    public function broadcastAs()
    {
        return 'NewNotification';
    }

    public function broadcastWith(): array
    {
        return [
            // chỗ này thử chia nhỏ xem sao, vd 'seen' => $this->notification->seen
            'notification' => new NotificationResource($this->notification)
        ];
    
    }
}
