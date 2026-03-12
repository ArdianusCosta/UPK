<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Peminjaman dengan Notifikasi",
    description: "API untuk mengelola transaksi peminjaman dengan notifikasi"
)]
class PeminjamanControllerWithNotification extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'alat_id' => 'required|exists:alat,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'jumlah' => 'required|integer|min:1',
            'keperluan' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $alat = Alat::findOrFail($validated['alat_id']);
            if ($alat->stok < $validated['jumlah']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stok tidak mencukupi'
                ], 400);
            }

            $peminjaman = Peminjaman::create([
                'kode_peminjaman' => 'PMJ-' . date('YmdHis') . '-' . rand(1000, 9999),
                'user_id' => $validated['user_id'],
                'alat_id' => $validated['alat_id'],
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_kembali' => $validated['tanggal_kembali'],
                'jumlah' => $validated['jumlah'],
                'keperluan' => $validated['keperluan'],
                'status' => 'pending',
            ]);

            $alat->decrement('stok', $validated['jumlah']);

            $this->notificationService->notifyPeminjaman($peminjaman, 'created');

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Peminjaman berhasil dibuat',
                'data' => $peminjaman->load(['user', 'alat'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat peminjaman: ' . $e->getMessage()
            ], 500);
        }
    }

    public function approve($id)
    {
        try {
            $peminjaman = Peminjaman::with(['user', 'alat'])->findOrFail($id);
            
            if ($peminjaman->status !== 'Pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Peminjaman tidak dapat disetujui'
                ], 400);
            }

            $peminjaman->update(['status' => 'approved']);

            $this->notificationService->notifyPeminjaman($peminjaman, 'approved');

            return response()->json([
                'status' => 'success',
                'message' => 'Peminjaman disetujui',
                'data' => $peminjaman
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyetujui peminjaman: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject($id)
    {
        try {
            $peminjaman = Peminjaman::with(['user', 'alat'])->findOrFail($id);
            
            if ($peminjaman->status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Peminjaman tidak dapat ditolak'
                ], 400);
            }

            $peminjaman->update(['status' => 'Dikembalikan']);

            $peminjaman->alat->increment('stok', $peminjaman->jumlah);

            $this->notificationService->notifyPeminjaman($peminjaman, 'Dikembalikan');

            return response()->json([
                'status' => 'success',
                'message' => 'Peminjaman ditolak',
                'data' => $peminjaman
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menolak peminjaman: ' . $e->getMessage()
            ], 500);
        }
    }
}
