<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterData\KategoriAlatController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

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
    });

    Route::prefix('pengembalians')->group(function () {
        Route::get('/trashed', [\App\Http\Controllers\Api\PengembalianController::class, 'trashed']);
        Route::post('/{id}/restore', [\App\Http\Controllers\Api\PengembalianController::class, 'restore']);
    });
    Route::apiResource('pengembalians', \App\Http\Controllers\Api\PengembalianController::class);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users-chat', [ChatController::class, 'users']);
        Route::get('/messages/{userId}', [ChatController::class, 'getMessages']);
        Route::post('/messages', [ChatController::class, 'sendMessage']);
        Route::post('/messages/{userId}/read', [ChatController::class, 'markAsRead']);
    });
});