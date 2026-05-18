<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;    

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = auth()->user()
            ->conversations()
            ->with(['participants', 'messages' => function($q) {
                $q->latest()->limit(1);
            }])
            ->latest()
            ->get()
            ->map(function($conv) {
                $conv->last_message = $conv->messages->first()?->body;
                return $conv;
            });

        return response()->json($conversations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:private,group',
            'name' => 'nullable|string',
            'participants' => 'required|array'
        ]);

        if ($request->type === 'private') {
            $participantId = $request->participants[0];
            
            $existing = auth()->user()
                ->conversations()
                ->where('type', 'private')
                ->whereHas('participants', function($q) use ($participantId) {
                    $q->where('users.id', $participantId);
                })
                ->first();

            if ($existing) {
                return response()->json($existing->load('participants'));
            }
        }

        $conversation = Conversation::create([
            'type' => $request->type,
            'name' => $request->name,
            'created_by' => auth()->id(),
        ]);

        $participants = array_unique([
            ...$request->participants,
            auth()->id()
        ]);

        $conversation->participants()
            ->attach($participants);

        return response()->json($conversation);
    }

    public function addMember(Request $request, $id)
    {
        $conversation = Conversation::findOrFail($id);

        $conversation->participants()
            ->syncWithoutDetaching([
                $request->user_id
            ]);

        return response()->json([
            'message' => 'Member added'
        ]);
    }

    public function removeMember($id, $userId)
    {
        $conversation = Conversation::findOrFail($id);

        $conversation->participants()
            ->detach($userId);

        return response()->json([
            'message' => 'Member removed'
        ]);
    }
}
