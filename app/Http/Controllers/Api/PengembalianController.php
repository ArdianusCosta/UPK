<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Pengembalian",
    description: "API untuk mengelola transaksi pengembalian alat"
)]
class PengembalianController extends Controller
{
    #[OA\Get(
        path: "/api/pengembalians",
        summary: "Ambil semua data pengembalian",
        security: [["bearerAuth" => []]],
        tags: ["Pengembalian"]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil data pengembalian",
        content: new OA\JsonContent(
            example: [
                "status" => "success",
                "data" => [
                    [
                        "id" => 1,
                        "peminjaman_id" => 1,
                        "tanggal_dikembalikan" => "2026-02-24 13:42:29",
                        "kondisi_kembali" => "baik",
                        "catatan" => "Kembali dalam keadaan utuh",
                        "peminjaman" => [
                            "id" => 1,
                            "kode" => "PINJAM-00001",
                            "peminjam" => ["id" => 1, "name" => "User Name"],
                            "alat" => ["id" => 1, "nama" => "Laptop Asus"]
                        ]
                    ]
                ]
            ]
        )
    )]
    public function index()
    {
        $query = Pengembalian::with(['peminjaman.peminjam', 'peminjaman.alat'])
            ->orderBy('created_at', 'desc');

        if (auth()->user()->hasRole('Peminjam')) {
            $query->whereHas('peminjaman', function ($q) {
                $q->where('peminjam_id', auth()->id());
            });
        }

        $pengembalians = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $pengembalians
        ]);
    }

    #[OA\Post(
        path: "/api/pengembalians",
        summary: "Catat pengembalian baru (bisa via Scan QR/Kode atau ID)",
        security: [["bearerAuth" => []]],
        tags: ["Pengembalian"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "kode_peminjaman", type: "string", example: "PINJAM-00001", description: "Wajib jika peminjaman_id kosong"),
                new OA\Property(property: "peminjaman_id", type: "integer", example: 1, description: "Wajib jika kode_peminjaman kosong"),
                new OA\Property(property: "kondisi_kembali", type: "string", enum: ["baik", "rusak", "hilang"], example: "baik"),
                new OA\Property(property: "catatan", type: "string", example: "Alat kembali lengkap"),
                new OA\Property(property: "foto", type: "string", format: "binary")
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Alat berhasil dikembalikan",
        content: new OA\JsonContent(
            example: [
                "status" => "success",
                "message" => "Alat berhasil dikembalikan.",
                "data" => ["id" => 1, "peminjaman_id" => 1]
            ]
        )
    )]
    public function store(Request $request)
    {
        $request->validate([
            'kode_peminjaman' => 'required_without:peminjaman_id|string|nullable',
            'peminjaman_id'   => 'required_without:kode_peminjaman|exists:peminjamen,id|nullable',
            'kondisi_kembali' => 'required|in:baik,rusak,hilang',
            'catatan'         => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $query = Peminjaman::query();

            if ($request->kode_peminjaman) {
                $query->where('kode', $request->kode_peminjaman);
            } else {
                $query->where('id', $request->peminjaman_id);
            }

            $peminjaman = $query->whereIn('status', ['Dipinjam', 'Terlambat'])->first();

            if (!$peminjaman) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data peminjaman tidak ditemukan atau sudah dikembalikan.',
                ], 404);
            }

            // 1. Create Pengembalian record
            $data = [
                'peminjaman_id' => $peminjaman->id,
                'tanggal_dikembalikan' => now(),
                'kondisi_kembali' => $request->kondisi_kembali,
                'catatan' => $request->catatan,
            ];

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/pengembalian'), $filename);
                $data['foto'] = $filename;
            }

            $pengembalian = Pengembalian::create($data);

            // 2. Update Peminjaman status
            $peminjaman->update([
                'status' => 'Dikembalikan',
            ]);

            // 3. Update Alat status and increment stock
            $alat = Alat::lockForUpdate()->find($peminjaman->alat_id);
            $alat->increment('stok');
            
            // If condition is damaged, we might want to change alat status to maintenance
            if ($request->kondisi_kembali === 'rusak') {
                $alat->update(['status' => 'maintenance']);
            } else {
                $alat->update(['status' => 'tersedia']);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Alat berhasil dikembalikan.',
                'data' => $pengembalian->load('peminjaman.alat', 'peminjaman.peminjam'),
            ], 201);
        });
    }

    #[OA\Get(
        path: "/api/pengembalians/{id}",
        summary: "Ambil detail pengembalian",
        security: [["bearerAuth" => []]],
        tags: ["Pengembalian"]
    )]
    #[OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil detail pengembalian"
    )]
    public function show(string $id)
    {
        $pengembalian = Pengembalian::with(['peminjaman.peminjam', 'peminjaman.alat'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $pengembalian
        ]);
    }

    #[OA\Patch(
        path: "/api/pengembalians/{id}",
        summary: "Update data pengembalian",
        security: [["bearerAuth" => []]],
        tags: ["Pengembalian"]
    )]
    #[OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "kondisi_kembali", type: "string", enum: ["baik", "rusak", "hilang"]),
                new OA\Property(property: "catatan", type: "string"),
                new OA\Property(property: "tanggal_dikembalikan", type: "string", format: "date-time")
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Data pengembalian berhasil diupdate"
    )]
    public function update(Request $request, string $id)
    {
        $pengembalian = Pengembalian::findOrFail($id);

        $request->validate([
            'kondisi_kembali'      => 'sometimes|in:baik,rusak,hilang',
            'catatan'              => 'sometimes|nullable|string',
            'tanggal_dikembalikan' => 'sometimes|date',
            'foto'                 => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = $request->only(['kondisi_kembali', 'catatan', 'tanggal_dikembalikan']);

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($pengembalian->foto && file_exists(public_path('uploads/pengembalian/' . $pengembalian->foto))) {
                unlink(public_path('uploads/pengembalian/' . $pengembalian->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/pengembalian'), $filename);
            $updateData['foto'] = $filename;
        }

        $pengembalian->update($updateData);

        return response()->json([
            'status' => 'success',
            'message' => 'Data pengembalian berhasil diupdate.',
            'data' => $pengembalian->load('peminjaman.alat', 'peminjaman.peminjam')
        ]);
    }

    #[OA\Delete(
        path: "/api/pengembalians/{id}",
        summary: "Hapus data pengembalian (Membatalkan pengembalian)",
        security: [["bearerAuth" => []]],
        tags: ["Pengembalian"]
    )]
    #[OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\Response(
        response: 200,
        description: "Data pengembalian berhasil dihapus"
    )]
    public function destroy(string $id)
    {
        return DB::transaction(function () use ($id) {
            $pengembalian = Pengembalian::findOrFail($id);
            
            // Logika Stok: Jika pengembalian dihapus, stok berkurang lagi 
            // karena alat dianggap belum benar-benar kembali (sesuai request user)
            $peminjaman = $pengembalian->peminjaman;
            if ($peminjaman) {
                $alat = Alat::lockForUpdate()->find($peminjaman->alat_id);
                if ($alat && $alat->stok > 0) {
                    $alat->decrement('stok');
                }
            }

            $pengembalian->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data pengembalian berhasil dihapus.'
            ]);
        });
    }

    #[OA\Get(
        path: "/api/pengembalians/trashed",
        summary: "Ambil data pengembalian yang dihapus (Trash)",
        security: [["bearerAuth" => []]],
        tags: ["Pengembalian"]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil data trashed"
    )]
    public function trashed()
    {
        $query = Pengembalian::onlyTrashed()
            ->with(['peminjaman.peminjam', 'peminjaman.alat'])
            ->orderBy('deleted_at', 'desc');

        if (auth()->user()->hasRole('Peminjam')) {
            $query->whereHas('peminjaman', function ($q) {
                $q->where('peminjam_id', auth()->id());
            });
        }

        $trashed = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $trashed
        ]);
    }

    #[OA\Post(
        path: "/api/pengembalians/{id}/restore",
        summary: "Kembalikan data pengembalian yang dihapus",
        security: [["bearerAuth" => []]],
        tags: ["Pengembalian"]
    )]
    #[OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))]
    #[OA\Response(
        response: 200,
        description: "Data berhasil di-restore"
    )]
    public function restore(string $id)
    {
        return DB::transaction(function () use ($id) {
            $pengembalian = Pengembalian::onlyTrashed()->findOrFail($id);
            
            // Logika Stok: Jika pengembalian di-restore, stok bertambah lagi
            $peminjaman = Peminjaman::find($pengembalian->peminjaman_id);
            if ($peminjaman) {
                $alat = Alat::lockForUpdate()->find($peminjaman->alat_id);
                if ($alat) {
                    $alat->increment('stok');
                }
            }

            $pengembalian->restore();

            return response()->json([
                'status' => 'success',
                'message' => 'Data pengembalian berhasil di-restore.',
                'data' => $pengembalian->load('peminjaman.alat', 'peminjaman.peminjam')
            ]);
        });
    }
}
