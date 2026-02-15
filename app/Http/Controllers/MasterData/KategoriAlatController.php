<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\MDKategoriAlat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Master Data - Kategori Alat",
    description: "API untuk mengelola kategori alat"
)]
class KategoriAlatController extends Controller
{
    #[OA\Get(
        path: "/api/master-data/kategori-alat",
        summary: "Ambil semua kategori alat",
        tags: ["Master Data - Kategori Alat"]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil data"
    )]
    public function index()
    {
        $kategoriAlats = MDKategoriAlat::all();

        return response()->json([
            'status' => 'success',
            'data' => $kategoriAlats,
        ], 200);
    }

    #[OA\Get(
        path: "/api/master-data/kategori-alat/active",
        summary: "Ambil kategori alat dengan status active",
        tags: ["Master Data - Kategori Alat"]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil data kategori active"
    )]
    public function getActiveKategori()
    {
        try {
            $kategoriAlats = MDKategoriAlat::where('status', 'active')->get();

            return response()->json([
                'status' => 'success',
                'data' => $kategoriAlats,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data kategori active',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[OA\Post(
        path: "/api/master-data/kategori-alat",
        summary: "Tambah kategori alat",
        tags: ["Master Data - Kategori Alat"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["nama_kategori_alat"],
            properties: [
                new OA\Property(property: "nama_kategori_alat", type: "string", example: "Elektronik"),
                new OA\Property(property: "status", type: "string", example: "active")
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Berhasil menambahkan kategori"
    )]
    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'nama_kategori_alat' => 'required|string|max:255',
                'status' => 'nullable|string|in:active,inactive',
            ]);

            DB::beginTransaction();
            $kategoriAlat = MDKategoriAlat::create($validated);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Master Data Kategori Alat Berhasil Ditambahkan',
                'data' => $kategoriAlat,
            ], 201);

        }catch (\Throwable $e){
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan',
            ], 500);
        }
    }

    #[OA\Patch(
        path: "/api/master-data/kategori-alat/{id}",
        summary: "Update kategori alat",
        tags: ["Master Data - Kategori Alat"]
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        description: "ID kategori",
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["nama_kategori_alat"],
            properties: [
                new OA\Property(property: "nama_kategori_alat", type: "string", example: "Elektronik Update"),
                new OA\Property(property: "status", type: "string", example: "inactive")
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil update kategori"
    )]
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kategori_alat' => 'required|string|max:255',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $kategoriAlat = MDKategoriAlat::findOrFail($id);
        $kategoriAlat->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Master Data Kategori Alat Berhasil Diperbarui',
            'data' => $kategoriAlat,
        ], 200);
    }

    #[OA\Delete(
        path: "/api/master-data/kategori-alat/{id}",
        summary: "Hapus kategori alat",
        tags: ["Master Data - Kategori Alat"]
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil hapus kategori"
    )]
    public function delete($id)
    {
        $kategoriAlat = MDKategoriAlat::findOrFail($id);
        $kategoriAlat->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Master Data Kategori Alat Berhasil Dihapus',
        ], 200);
    }
}
