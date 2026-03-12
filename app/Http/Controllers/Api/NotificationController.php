<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = $this->notificationService->getUnreadNotifications($user);
        
        return response()->json([
            'success' => true,
            'data' => $notifications,
            'unread_count' => $notifications->count(),
        ]);
    }

    public function all(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }

    public function markAsRead(Request $request, $id): JsonResponse
    {
        $notification = $this->notificationService->markAsRead($id);
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->notificationService->markAllAsRead($user);
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $user = $request->user();
        $count = $user->unreadNotifications()->count();
        
        return response()->json([
            'success' => true,
            'unread_count' => $count,
        ]);
    }

    public function triggerPeminjamanNotification(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'action' => 'required|in:created,dipinjam,rejected,returned',
        ]);

        $peminjaman = \App\Models\Peminjaman::with(['peminjam', 'alat'])->find($validated['peminjaman_id']);
        
        $this->notificationService->notifyPeminjaman($peminjaman, $validated['action']);

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman notification sent successfully',
        ]);
    }

    public function triggerPengembalianNotification(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pengembalian_id' => 'required|exists:pengembalian,id',
            'action' => 'required|in:created,approved,rejected',
        ]);

        $pengembalian = \App\Models\Pengembalian::with(['peminjaman.user', 'peminjaman.alat'])->find($validated['pengembalian_id']);
        
        $this->notificationService->notifyPengembalian($pengembalian, $validated['action']);

        return response()->json([
            'success' => true,
            'message' => 'Pengembalian notification sent successfully',
        ]);
    }

    public function triggerAlatNotification(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'alat_id' => 'required|exists:alat,id',
            'action' => 'required|in:created,updated,deleted,low_stock',
        ]);

        $alat = \App\Models\Alat::with('kategori')->find($validated['alat_id']);
        
        $this->notificationService->notifyAlat($alat, $validated['action']);

        return response()->json([
            'success' => true,
            'message' => 'Alat notification sent successfully',
        ]);
    }
}
