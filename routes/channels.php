<?php

use App\Models\ChatParticipant;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{id}', function ($user, $id) {
    $participants = ChatParticipant::where([
        [
            'user_id', $user->id
        ],
        [
            'chat_id', $id
        ]
    ])->first();

    return $participants !== null;
});
