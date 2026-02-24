<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id}', function ($user, $id) {
    \Log::info('Broadcasting Auth Check:', [
        'authenticated_user_id' => $user->id,
        'requested_channel_id' => $id,
        'match' => (int) $user->id === (int) $id
    ]);
    return (int) $user->id === (int) $id;
});