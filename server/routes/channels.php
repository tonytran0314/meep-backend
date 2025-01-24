<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['api', 'auth:sanctum']]);

Broadcast::channel('room.{roomId}', function ($user, $roomId) {
    // Ensure user is part of the room
    return $user->rooms()->where('rooms.id', $roomId)->exists();
});
