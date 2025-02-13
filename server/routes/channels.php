<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['api', 'auth:sanctum']]);

Broadcast::channel('room.{roomId}', function ($user, $roomId) {
    // Ensure user is part of the room
    return $user->rooms()->where('rooms.id', $roomId)->exists();
});

Broadcast::channel('notification.{userId}', function ($user) {
    return $user !== null;
});

Broadcast::channel('new-room.{userId}', function ($user) {
    return $user !== null;
});
