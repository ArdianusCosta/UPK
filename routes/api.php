<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\MasterData\KategoriAlatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('master-data')->group(function() {
    Route::get('/kategori-alat', [KategoriAlatController::class, 'index']);
    Route::post('/kategori-alat', [KategoriAlatController::class, 'store']);
    Route::patch('/kategori-alat/{id}', [KategoriAlatController::class, 'update']);
    Route::delete('/kategori-alat/{id}', [KategoriAlatController::class, 'delete']);
});

Route::prefix('alats')->group(function() {
    Route::get('/', [AlatController::class, 'index']);
    Route::get('/{id}', [AlatController::class, 'show']);
    Route::post('/', [AlatController::class, 'store']);
    Route::patch('/{id}', [AlatController::class, 'update']);
    Route::delete('/{id}', [AlatController::class, 'delete']);
});