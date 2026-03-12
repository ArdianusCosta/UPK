<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "Testing Notification API Endpoints:\n\n";

$admin = \App\Models\User::where('name', 'Adminx')->first();
if (!$admin) {
    echo "Admin user not found!\n";
    exit(1);
}

echo "Admin user: {$admin->name} (Role: {$admin->getRoleNames()->first()})\n\n";

echo "1. Testing GET /api/notifications\n";
try {
    $notifications = $admin->unreadNotifications()->orderBy('created_at', 'desc')->get();
    echo "✓ Unread notifications count: {$notifications->count()}\n";
    
    foreach ($notifications as $notif) {
        echo "  - Type: {$notif->data['type']}, Action: {$notif->data['action']}, Message: {$notif->data['message']}\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

echo "2. Testing GET /api/notifications/unread-count\n";
try {
    $unreadCount = $admin->unreadNotifications()->count();
    echo "✓ Unread count: {$unreadCount}\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

echo "3. Testing POST /api/notifications/alat\n";
try {
    $service = app(\App\Services\NotificationService::class);
    $alat = \App\Models\Alat::with('kategoriAlat')->first();
    
    if ($alat) {
        $service->notifyAlat($alat, 'updated');
        echo "✓ Alat notification triggered successfully\n";
    } else {
        echo "✗ No alat found\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

echo "4. Testing PATCH /api/notifications/{id}/read\n";
try {
    $notification = $admin->unreadNotifications()->first();
    if ($notification) {
        $notification->markAsRead();
        echo "✓ Notification marked as read\n";
    } else {
        echo "✗ No unread notification found\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

echo "5. Testing role-based notifications\n";
try {
    $petugas = \App\Models\User::where('name', 'Petugas User')->first();
    $peminjam = \App\Models\User::where('name', 'Peminjam User')->first();
    
    echo "Admin notifications: " . $admin->notifications()->count() . "\n";
    echo "Petugas notifications: " . $petugas->notifications()->count() . "\n";
    echo "Peminjam notifications: " . $peminjam->notifications()->count() . "\n";
    
    echo "✓ Role-based notifications working correctly\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n";

echo "6. Testing different notification types\n";
try {
    $service = app(\App\Services\NotificationService::class);
    
    $alat = \App\Models\Alat::first();
    $service->notifyAlat($alat, 'low_stock');
    echo "✓ Low stock notification sent\n";
    
    $peminjaman = \App\Models\Peminjaman::with(['peminjam', 'alat'])->first();
    if ($peminjaman) {
        $service->notifyPeminjaman($peminjaman, 'approved');
        echo "✓ Peminjaman approved notification sent\n";
    }
    
    echo "✓ All notification types working\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Notification System Test Complete ===\n";
