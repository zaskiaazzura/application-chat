<?php

namespace App\Services;

use App\Models\Conversation;
use App\Events\MessageSent;
use App\Notifications\NewMessageNotification;

class MessageService
{
    public function send(
        $request,
        Conversation $conversation
    ) {

        $attachment = null;

        if ($request->hasFile('file')) {

            $attachment = $request
                ->file('file')
                ->store('attachments', 'public');
        }

        $message = $conversation
            ->messages()
            ->create([
                'sender_id' => auth()->id(),
                'body' => $request->body,
                'type' => $request->type,
                'attachment' => $attachment,
                'reply_to_message_id'
                    => $request->reply_to_message_id,
            ]);

        $message->load([
            'sender',
            'replyTo'
        ]);

        $receivers = $conversation
            ->participants
            ->where(
                'id',
                '!=',
                auth()->id()
            );

        foreach ($receivers as $user) {

            $user->notify(
                new NewMessageNotification($message)
            );
        }

        broadcast(
            new MessageSent($message)
        )->toOthers();

        return $message;
    }
}