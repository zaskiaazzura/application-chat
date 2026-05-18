<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel(
    'conversation.{id}',
    function ($user, $id) {

        return Conversation::find($id)
            ?->participants()
            ->where('users.id', $user->id)
            ->exists();
    }
);

Broadcast::channel(
    'online',
    function ($user) {

        return [
            'id' => $user->id,
            'name' => $user->name,
        ];
    }
);