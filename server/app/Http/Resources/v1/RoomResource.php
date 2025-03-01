<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $otherUser = null;

        if (!$this->is_group) {
            $otherUser = $this->users
                ->where('id', '!=', Auth::user()->id)
                ->first();
        }

        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'isGroup' => $this->is_group,
            'name' => $this->is_group ? $this->name : ($otherUser?->name ?? 'Unknown'),
            'latestMessage' => $this->messages->first() ? [
                'id' => $this->messages->first()->id,
                'content' => $this->messages->first()->content,
                'created_at' => $this->messages->first()->created_at,
            ] : null,
        ];
    }
}
