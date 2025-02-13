<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'seen' => $this->seen,
            'senderId' => $this->sender_id,
            'senderName' => $this->sender->name,
            'receiverId' => $this->receiver_id,
            'type' => $this->type,
            'time' => $this->created_at
        ];
    }
}
