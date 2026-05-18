<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Notifications\NewMessageNotification;
use App\Services\MessageService;

class MessageController extends Controller
{
    public function index($conversationId)
    {
        $messages = Message::with([
                'sender',
                'replyTo'
            ])
            ->where('conversation_id', $conversationId)
            ->latest()
            ->paginate(20);

        Message::where('conversation_id', $conversationId)
            ->whereNull('read_at')
            ->where('sender_id', '!=', auth()->id())
            ->update([
                'read_at' => now()
            ]);

        return response()->json($messages);
    }

    public function store(
        Request $request,
        $conversationId,
        MessageService $messageService
    )
    {
        $request->validate([
            'body' => 'nullable|string',
            'type' => 'required|in:text,image,file',
            'file' => 'nullable|file|max:10240',
            'reply_to_message_id'
                => 'nullable|exists:messages,id'
        ]);

        $conversation = Conversation::findOrFail(
            $conversationId
        );

        $message = $messageService->send(
            $request,
            $conversation
        );

        return response()->json($message);
    }
    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id !== auth()->id()) {
            abort(403);
        }

        $message->delete();

        return response()->json([
            'message' => 'Message deleted'
        ]);
    }

    public function markAsRead($id)
    {
        $message = Message::findOrFail($id);

        $message->update([
            'read_at' => now()
        ]);

        return response()->json([
            'message' => 'Message read'
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->search;

        $messages = Message::with('sender')
            ->where('body', 'like', "%{$search}%")
            ->latest()
            ->get();

        return response()->json($messages);
    }
}
