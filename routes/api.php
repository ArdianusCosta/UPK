<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterData\KategoriAlatController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PengembalianController;
use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login']);
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);

    Route::get('/user', [AuthController::class, 'getUser']);
    Route::patch('/user/update', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::prefix('master-data')->group(function () {
        Route::get('/kategori-alat', [KategoriAlatController::class, 'index'])->middleware('permission:kategori.view');
        Route::get('/kategori-alat/active', [KategoriAlatController::class, 'getActiveKategori'])->middleware('permission:kategori.view');
        Route::post('/kategori-alat', [KategoriAlatController::class, 'store'])->middleware('permission:kategori.create');
        Route::patch('/kategori-alat/{id}', [KategoriAlatController::class, 'update'])->middleware('permission:kategori.update');
        Route::delete('/kategori-alat/{id}', [KategoriAlatController::class, 'delete'])->middleware('permission:kategori.delete');
    });

    Route::prefix('alats')->group(function () {
        Route::get('/', [AlatController::class, 'index'])->middleware('permission:alat.view');
        Route::get('/{id}', [AlatController::class, 'show'])->middleware('permission:alat.view');
        Route::post('/', [AlatController::class, 'store'])->middleware('permission:alat.create');
        Route::patch('/{id}', [AlatController::class, 'update'])->middleware('permission:alat.update');
        Route::delete('/{id}', [AlatController::class, 'delete'])->middleware('permission:alat.delete');
    });

    Route::prefix('peminjamans')->group(function () {
        Route::get('/', [PeminjamanController::class, 'index'])->middleware('permission:peminjaman.view');
        Route::post('/', [PeminjamanController::class, 'store'])->middleware('permission:peminjaman.create');
        Route::get('/{id}', [PeminjamanController::class, 'show'])->middleware('permission:peminjaman.view');
        Route::patch('/{id}', [PeminjamanController::class, 'update'])->middleware('permission:peminjaman.update');
        Route::delete('/{id}', [PeminjamanController::class, 'destroy'])->middleware('permission:peminjaman.delete');
        
        // Approval
        Route::post('/{id}/approve', [PeminjamanController::class, 'approve'])->middleware('permission:peminjaman.approve');
        Route::post('/{id}/reject', [PeminjamanController::class, 'reject'])->middleware('permission:peminjaman.approve');
        
        // Download Receipt
        Route::get('/{id}/download', [PeminjamanController::class, 'downloadReceipt'])->middleware('permission:peminjaman.view');
    });

    Route::prefix('pengembalians')->group(function () {
        Route::get('/', [PengembalianController::class, 'index'])->middleware('permission:pengembalian.view');
        Route::post('/', [PengembalianController::class, 'store'])->middleware('permission:pengembalian.create');
        Route::get('/trashed', [PengembalianController::class, 'trashed'])->middleware('permission:pengembalian.view');
        Route::get('/{id}', [PengembalianController::class, 'show'])->middleware('permission:pengembalian.view');
        Route::patch('/{id}', [PengembalianController::class, 'update'])->middleware('permission:pengembalian.update');
        Route::delete('/{id}', [PengembalianController::class, 'destroy'])->middleware('permission:pengembalian.delete');
        Route::post('/{id}/restore', [PengembalianController::class, 'restore'])->middleware('permission:pengembalian.view');
    });

    // Chat
    Route::get('/users-chat', [ChatController::class, 'users']);
    Route::get('/messages/{userId}', [ChatController::class, 'getMessages']);
    Route::post('/messages', [ChatController::class, 'sendMessage']);
    Route::patch('/messages/{id}', [ChatController::class, 'update']);
    Route::delete('/messages/{id}', [ChatController::class, 'destroyMessage']);
    Route::post('/messages/{userId}/read', [ChatController::class, 'markAsRead']);

    // User Profile
    Route::get('/users-profile', [UserController::class, 'index'])->middleware('permission:users.view');
    Route::post('/users-profile', [UserController::class, 'store'])->middleware('permission:users.create');
    Route::patch('/users-profile/{id}', [UserController::class, 'update'])->middleware('permission:users.update');
    Route::delete('/users-profile/{id}', [UserController::class, 'destroy'])->middleware('permission:users.delete');

    // Roles
    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:role.view');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:role.create');
    Route::patch('/roles/{id}', [RoleController::class, 'update'])->middleware('permission:role.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->middleware('permission:role.delete');

    // Permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:permission.view');
    Route::post('/permissions', [PermissionController::class, 'store'])->middleware('permission:permission.create');
    Route::patch('/permissions/{id}', [PermissionController::class, 'update'])->middleware('permission:permission.update');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->middleware('permission:permission.delete');

    Route::prefix('laporan')->middleware('permission:laporan.view')->group(function () {
        Route::get('/', [LaporanController::class, 'index']);
        Route::get('/export-excel', [LaporanController::class, 'exportExcel']);
        Route::get('/export-pdf', [LaporanController::class, 'exportPdf']);
    });

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->middleware('permission:log.view');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/all', [NotificationController::class, 'all']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    
    // Trigger notifications (for frontend to call)
    Route::post('/notifications/peminjaman', [NotificationController::class, 'triggerPeminjamanNotification']);
    Route::post('/notifications/pengembalian', [NotificationController::class, 'triggerPengembalianNotification']);
    Route::post('/notifications/alat', [NotificationController::class, 'triggerAlatNotification']);
});