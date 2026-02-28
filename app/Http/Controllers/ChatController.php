<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Models\Message;

class ChatController extends Controller
{
    public function users(Request $request)
    {
        return User::where('id', '!=', $request->user()->id)
            ->select('id', 'name')
            ->withCount(['messagesSent as unread_count' => function ($query) use ($request) {
                $query->where('receiver_id', $request->user()->id)
                      ->where('is_read', false);
            }])
            ->get();
    }

    public function getMessages(Request $request, $userId)
    {
        // Mark as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return Message::where(function ($query) use ($userId, $request) {
            $query->where('sender_id', $request->user()->id)
                  ->where('receiver_id', $userId);
        })
        ->orWhere(function ($query) use ($userId, $request) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $request->user()->id);
        })
        ->with('sender:id,name')
        ->orderBy('created_at')
        ->get();
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if (!$request->message && !$request->hasFile('image')) {
            return response()->json(['message' => 'Message or image is required'], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat', 'public');
        }

        $message = Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'image' => $imagePath,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return $message->load('sender:id,name');
    }

    public function destroyMessage(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messageData = $message->toArray();
        $message->delete();

        broadcast(new \App\Events\MessageDeleted($messageData))->toOthers();

        return response()->json(['status' => 'success']);
    }

    public function markAsRead(Request $request, $userId)
    {
        Message::where('sender_id', $userId)
            ->where('receiver_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'success']);
    }
}