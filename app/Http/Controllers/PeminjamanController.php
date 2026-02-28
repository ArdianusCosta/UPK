<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Peminjaman",
    description: "API untuk mengelola transaksi peminjaman dan pengembalian alat"
)]
class PeminjamanController extends Controller
{
    #[OA\Get(
        path: "/api/peminjamans",
        summary: "Ambil semua data peminjaman",
        security: [["bearerAuth" => []]],
        tags: ["Peminjaman"]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil data peminjaman",
        content: new OA\JsonContent(
            example: [
                "status" => "success",
                "data" => [
                    [
                        "id" => 1,
                        "kode" => "PINJAM-00001",
                        "peminjam_id" => 1,
                        "alat_id" => 1,
                        "tanggal_pinjam" => "2026-02-23",
                        "status" => "Dipinjam",
                        "peminjam" => ["id" => 1, "name" => "User Name"],
                        "alat" => ["id" => 1, "nama" => "Laptop Asus", "foto" => "image.jpg"]
                    ]
                ]
            ]
        )
    )]
    public function index()
    {
        $peminjamans = Peminjaman::with(['peminjam:id,name', 'alat:id,nama,foto'])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $peminjamans
        ]);
    }

    #[OA\Post(
        path: "/api/peminjamans",
        summary: "Buat peminjaman baru",
        security: [["bearerAuth" => []]],
        tags: ["Peminjaman"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["alat_id", "peminjam_id"],
            properties: [
                new OA\Property(property: "alat_id", type: "integer", example: 1),
                new OA\Property(property: "peminjam_id", type: "integer", example: 1),
                new OA\Property(property: "tanggal_pinjam", type: "string", format: "date", example: "2026-02-23"),
                new OA\Property(property: "status", type: "string", example: "Dipinjam")
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Peminjaman berhasil dibuat",
        content: new OA\JsonContent(
            example: [
                "status" => "success",
                "message" => "Peminjaman berhasil dicatat",
                "data" => ["id" => 1, "kode" => "PINJAM-00001"]
            ]
        )
    )]
    public function store(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|exists:alats,id',
            'peminjam_id' => 'required|exists:users,id',
            'tanggal_pinjam' => 'nullable|date',
            'status' => 'nullable|string|in:Dipinjam,Terlambat',
        ]);

        return DB::transaction(function () use ($request) {
            $alat = Alat::lockForUpdate()->find($request->alat_id);

            if ($alat->stok <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stok alat tidak mencukupi'
                ], 422);
            }

            // $alat->decrement('stok'); // Stok dikurangi saat disetujui petugas

            $peminjaman = Peminjaman::create([
                'peminjam_id' => $request->peminjam_id,
                'alat_id' => $request->alat_id,
                'tanggal_pinjam' => $request->tanggal_pinjam ?? null,
                'status' => $request->status ?? 'Pending',
            ]);

            $peminjaman->refresh();

            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan peminjaman berhasil dikirim, menunggu persetujuan petugas.',
                'data' => $peminjaman->load(['peminjam:id,name', 'alat:id,nama,foto'])
            ], 201);
        });
    }

    #[OA\Get(
        path: "/api/peminjamans/{id}",
        summary: "Ambil detail peminjaman",
        security: [["bearerAuth" => []]],
        tags: ["Peminjaman"]
    )]
    #[OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil detail peminjaman",
        content: new OA\JsonContent(
            example: [
                "status" => "success",
                "data" => [
                    "id" => 1,
                    "kode" => "PINJAM-00001",
                    "peminjam" => ["id" => 1, "name" => "User Name"],
                    "alat" => ["id" => 1, "nama" => "Laptop Asus", "foto" => "image.jpg"]
                ]
            ]
        )
    )]
    public function show($id)
    {
        $peminjaman = Peminjaman::with(['peminjam:id,name', 'alat:id,nama,foto'])->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $peminjaman
        ]);
    }

    #[OA\Patch(
        path: "/api/peminjamans/{id}",
        summary: "Update data peminjaman",
        security: [["bearerAuth" => []]],
        tags: ["Peminjaman"]
    )]
    #[OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "tanggal_pinjam", type: "string", format: "date"),
                new OA\Property(property: "status", type: "string", enum: ["Dipinjam", "Terlambat", "Dikembalikan"])
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Data peminjaman berhasil diperbarui",
        content: new OA\JsonContent(
            example: [
                "status" => "success",
                "message" => "Data peminjaman berhasil diperbarui"
            ]
        )
    )]
    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        $validated = $request->validate([
            'tanggal_pinjam' => 'sometimes|date',
            'status' => 'sometimes|string|in:Pending,Dipinjam,Ditolak,Terlambat,Dikembalikan',
        ]);

        return DB::transaction(function () use ($request, $peminjaman, $validated) {
            $peminjaman->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Data peminjaman berhasil diperbarui',
                'data' => $peminjaman->load(['peminjam:id,name', 'alat:id,nama,foto'])
            ]);
        });
    }

    #[OA\Post(
        path: "/api/peminjamans/{id}/approve",
        summary: "Setujui pengajuan peminjaman",
        security: [["bearerAuth" => []]],
        tags: ["Peminjaman"]
    )]
    #[OA\Response(
        response: 200,
        description: "Peminjaman disetujui",
        content: new OA\JsonContent(
            example: [
                "status" => "success",
                "message" => "Peminjaman disetujui",
                "data" => ["id" => 1, "status" => "Dipinjam"]
            ]
        )
    )]
    public function approve(Request $request, $id)
    {
        return DB::transaction(function () use ($id) {
            $peminjaman = Peminjaman::findOrFail($id);

            if ($peminjaman->status !== 'Pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Hanya pengajuan berstatus Pending yang dapat disetujui'
                ], 422);
            }

            $alat = Alat::lockForUpdate()->find($peminjaman->alat_id);

            if ($alat->stok <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stok alat tidak mencukupi untuk menyetujui peminjaman ini'
                ], 422);
            }

            $alat->decrement('stok');

            $peminjaman->update([
                'status' => 'Dipinjam',
                'tanggal_pinjam' => now(),
                'petugas_id' => auth()->id()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Peminjaman berhasil disetujui',
                'data' => $peminjaman->load(['peminjam:id,name', 'alat:id,nama,foto', 'petugas:id,name'])
            ]);
        });
    }

    #[OA\Post(
        path: "/api/peminjamans/{id}/reject",
        summary: "Tolak pengajuan peminjaman",
        security: [["bearerAuth" => []]],
        tags: ["Peminjaman"]
    )]
    #[OA\Response(
        response: 200,
        description: "Peminjaman ditolak",
        content: new OA\JsonContent(
            example: [
                "status" => "success",
                "message" => "Peminjaman ditolak"
            ]
        )
    )]
    public function reject(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'Pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya pengajuan berstatus Pending yang dapat ditolak'
            ], 422);
        }

        $peminjaman->update([
            'status' => 'Ditolak',
            'petugas_id' => auth()->id()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Peminjaman telah ditolak',
            'data' => $peminjaman->load(['peminjam:id,name', 'alat:id,nama,foto', 'petugas:id,name'])
        ]);
    }



    #[OA\Delete(
        path: "/api/peminjamans/{id}",
        summary: "Hapus/Batalkan peminjaman (Kembalikan stok)",
        security: [["bearerAuth" => []]],
        tags: ["Peminjaman"]
    )]
    #[OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\Response(
        response: 200,
        description: "Data peminjaman berhasil dihapus",
        content: new OA\JsonContent(
            example: [
                "status" => "success",
                "message" => "Data peminjaman dihapus"
            ]
        )
    )]
    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $peminjaman = Peminjaman::findOrFail($id);
            
            if ($peminjaman->status !== 'Dikembalikan') {
                $alat = Alat::lockForUpdate()->find($peminjaman->alat_id);
                $alat->increment('stok');
            }

            $peminjaman->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data peminjaman dihapus'
            ]);
        });
    }
}
