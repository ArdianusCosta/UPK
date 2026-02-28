<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterData\KategoriAlatController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);

    Route::get('/user', [AuthController::class, 'getUser']);
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
        Route::get('/', [PeminjamanController::class, 'index']);
        Route::post('/', [PeminjamanController::class, 'store']);
        Route::get('/{id}', [PeminjamanController::class, 'show']);
        Route::delete('/{id}', [PeminjamanController::class, 'destroy']);
        
        // Approval
        Route::post('/{id}/approve', [PeminjamanController::class, 'approve'])->middleware('permission:peminjaman.approve');
        Route::post('/{id}/reject', [PeminjamanController::class, 'reject'])->middleware('permission:peminjaman.approve');
    });

    Route::prefix('pengembalians')->group(function () {
        Route::get('/trashed', [\App\Http\Controllers\Api\PengembalianController::class, 'trashed'])->middleware('permission:pengembalian.view');
        Route::post('/{id}/restore', [\App\Http\Controllers\Api\PengembalianController::class, 'restore'])->middleware('permission:pengembalian.view');
    });
    Route::apiResource('pengembalians', \App\Http\Controllers\Api\PengembalianController::class)->middleware([
        'index' => 'permission:pengembalian.view',
        'show' => 'permission:pengembalian.view',
        'store' => 'permission:pengembalian.create',
        'update' => 'permission:pengembalian.update',
        'destroy' => 'permission:pengembalian.delete',
    ]);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users-chat', [ChatController::class, 'users']);
        Route::get('/messages/{userId}', [ChatController::class, 'getMessages']);
        Route::post('/messages', [ChatController::class, 'sendMessage']);
        Route::delete('/messages/{id}', [ChatController::class, 'destroyMessage']);
        Route::post('/messages/{userId}/read', [ChatController::class, 'markAsRead']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users-profile', [UserController::class, 'index']);
        Route::patch('/users-profile/{id}', [UserController::class, 'update']);
    });
});