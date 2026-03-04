<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Events\MessageUpdated;
use App\Models\Message;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Chat",
    description: "API untuk fitur chat antar user"
)]
class ChatController extends Controller
{
    #[OA\Get(
        path: "/api/chat/users",
        summary: "Ambil daftar user untuk chat",
        security: [["bearerAuth" => []]],
        tags: ["Chat"]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil daftar user"
    )]
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

    #[OA\Get(
        path: "/api/chat/messages/{userId}",
        summary: "Ambil pesan chat dengan user tertentu",
        security: [["bearerAuth" => []]],
        tags: ["Chat"],
        parameters: [
            new OA\Parameter(name: "userId", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil pesan chat"
    )]
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

    #[OA\Post(
        path: "/api/chat/messages",
        summary: "Kirim pesan chat",
        security: [["bearerAuth" => []]],
        tags: ["Chat"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["receiver_id"],
                    properties: [
                        new OA\Property(property: "receiver_id", type: "integer"),
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "image", type: "string", format: "binary")
                    ]
                )
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Pesan berhasil dikirim"
    )]
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'file' => 'nullable|file|max:10240'
        ]);

        if (!$request->message && !$request->hasFile('image') && !$request->hasFile('file')) {
            return response()->json(['message' => 'Message, image or file is required'], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat', 'public');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('chat/files', 'public');
        }

        $message = Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'image' => $imagePath,
            'file' => $filePath,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return $message->load('sender:id,name');
    }

    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $message->update([
            'message' => $request->message,
        ]);

        broadcast(new MessageUpdated($message))->toOthers();

        return $message->load('sender:id,name');
    }

    #[OA\Delete(
        path: "/api/chat/messages/{id}",
        summary: "Hapus pesan chat",
        security: [["bearerAuth" => []]],
        tags: ["Chat"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ]
    )]
    #[OA\Response(
        response: 200,
        description: "Pesan berhasil dihapus"
    )]
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

    #[OA\Post(
        path: "/api/chat/messages/{userId}/read",
        summary: "Tandai pesan sebagai sudah dibaca",
        security: [["bearerAuth" => []]],
        tags: ["Chat"],
        parameters: [
            new OA\Parameter(name: "userId", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ]
    )]
    #[OA\Response(
        response: 200,
        description: "Pesan berhasil ditandai sebagai dibaca"
    )]
    public function markAsRead(Request $request, $userId)
    {
        Message::where('sender_id', $userId)
            ->where('receiver_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'success']);
    }
}