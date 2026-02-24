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
            'message' => 'required|string'
        ]);

        $message = Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return $message->load('sender:id,name');
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