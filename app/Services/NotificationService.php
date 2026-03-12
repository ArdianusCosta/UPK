<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\PeminjamanNotification;
use App\Notifications\PengembalianNotification;
use App\Notifications\AlatNotification;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Alat;

class NotificationService
{
    public function notifyPeminjaman(Peminjaman $peminjaman, string $action)
    {
        $peminjaman->load(['peminjam', 'alat']);
        
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PeminjamanNotification($peminjaman, $action));
        }

        $petugas = User::role('petugas')->get();
        foreach ($petugas as $user) {
            $user->notify(new PeminjamanNotification($peminjaman, $action));
        }

        if ($action === 'dipinjam' || $action === 'rejected') {
            $peminjaman->peminjam->notify(new PeminjamanNotification($peminjaman, $action));
        }
    }

    public function notifyPengembalian(Pengembalian $pengembalian, string $action)
    {
        $pengembalian->load(['peminjaman.peminjam', 'peminjaman.alat']);
        
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PengembalianNotification($pengembalian, $action));
        }
        $petugas = User::role('petugas')->get();
        foreach ($petugas as $user) {
            $user->notify(new PengembalianNotification($pengembalian, $action));
        }

        if ($action === 'approved' || $action === 'rejected') {
            $pengembalian->peminjaman->peminjam->notify(new PengembalianNotification($pengembalian, $action));
        }
    }

    public function notifyAlat(Alat $alat, string $action)
    {        $alat->load('kategoriAlat');
        
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new AlatNotification($alat, $action));
        }
        $petugas = User::role('petugas')->get();
        foreach ($petugas as $user) {
            $user->notify(new AlatNotification($alat, $action));
        }
    }

    public function checkLowStock()
    {
        $alatList = Alat::where('stok', '<=', 5)->get();
        
        foreach ($alatList as $alat) {
            $this->notifyAlat($alat, 'low_stock');
        }
    }

    public function getUnreadNotifications($user)
    {
        return $user->unreadNotifications()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
        return $notification;
    }

    public function markAllAsRead($user)
    {
        return $user->unreadNotifications()->update(['read_at' => now()]);
    }
}
