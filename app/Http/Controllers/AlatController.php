<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Alat",
    description: "API untuk mengelola data alat"
)]
class AlatController extends Controller
{
    #[OA\Get(
        path: "/api/alats",
        summary: "Ambil semua alat",
        tags: ["Alat"]
    )]
    #[OA\Response(response: 200, description: "Berhasil mengambil data")]
    public function index()
    {
        try {
            $alats = Alat::with('kategoriAlat')->get();

            return response()->json([
                'status' => 'success',
                'data' => $alats,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data alat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[OA\Get(
        path: "/api/alats/{id}",
        summary: "Ambil detail alat",
        tags: ["Alat"]
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(response: 200, description: "Berhasil mengambil detail")]
    public function show($id)
    {
        try {
            $alat = Alat::with('kategoriAlat')->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $alat,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alat tidak ditemukan'
            ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil detail alat',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    #[OA\Post(
        path: "/api/alats",
        summary: "Tambah alat",
        tags: ["Alat"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["kode","nama"],
            properties: [
                new OA\Property(property: "kode", type: "string", example: "A001"),
                new OA\Property(property: "nama", type: "string", example: "Laptop Asus"),
                new OA\Property(property: "kategori_alat_id", type: "integer", example: 1),
                new OA\Property(property: "kondisi", type: "string", example: "baik"),
                new OA\Property(property: "deskripsi", type: "string", example: "Laptop untuk keperluan desain"),
                new OA\Property(property: "lokasi", type: "string", example: "Lab Komputer"),
                new OA\Property(property: "foto", type: "string", format: "binary", example: "foto_alat.jpg"),
                new OA\Property(property: "stok", type: "integer", example: 10),
                new OA\Property(property: "status", type: "string", example: "tersedia")
            ]
        )
    )]
    #[OA\Response(response: 201, description: "Berhasil menambahkan alat")]
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kode' => 'required|string|unique:alats,kode',
                'nama' => 'required|string|max:255',
                'kategori_alat_id' => 'nullable|exists:m_d_kategori_alats,id',
                'kondisi' => 'nullable|in:baik,sedang,rusak',
                'deskripsi' => 'nullable|string',
                'lokasi' => 'nullable|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'stok' => 'nullable|integer|min:0',
                'status' => 'nullable|string|in:tersedia,dipinjam,maintenance',
            ]);

            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoName = time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/alat'), $fotoName);
                $validated['foto'] = $fotoName;
            }

            $alat = Alat::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Alat berhasil ditambahkan',
                'data' => $alat,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan alat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[OA\Patch(
        path: "/api/alats/{id}",
        summary: "Update alat",
        tags: ["Alat"]
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "kode", type: "string", example: "A001"),
                new OA\Property(property: "nama", type: "string", example: "Laptop Asus"),
                new OA\Property(property: "kategori_alat_id", type: "integer", example: 1),
                new OA\Property(property: "kondisi", type: "string", example: "baik"),
                new OA\Property(property: "deskripsi", type: "string", example: "Laptop untuk keperluan desain"),
                new OA\Property(property: "lokasi", type: "string", example: "Lab Komputer"),
                new OA\Property(property: "foto", type: "string", format: "binary", example: "foto_alat.jpg"),
                new OA\Property(property: "stok", type: "integer", example: 10),
                new OA\Property(property: "status", type: "string", example: "tersedia")
            ]
        )
    )]
    #[OA\Response(response: 200, description: "Berhasil update")]
    public function update(Request $request, $id)
    {
        try {
            $alat = Alat::findOrFail($id);

            $validated = $request->validate([
                'kode' => 'sometimes|string|unique:alats,kode,' . $id,
                'nama' => 'sometimes|string|max:255',
                'kategori_alat_id' => 'nullable|exists:m_d_kategori_alats,id',
                'kondisi' => 'nullable|in:baik,sedang,rusak',
                'deskripsi' => 'nullable|string',
                'lokasi' => 'nullable|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'stok' => 'nullable|integer|min:0',
                'status' => 'nullable|string|in:tersedia,dipinjam,maintenance',
            ]);

            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoName = time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('uploads/alat'), $fotoName);
                $validated['foto'] = $fotoName;
            }

            $alat->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Alat berhasil diperbarui',
                'data' => $alat,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alat tidak ditemukan'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui alat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[OA\Delete(
        path: "/api/alats/{id}",
        summary: "Hapus alat",
        tags: ["Alat"]
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(response: 200, description: "Berhasil hapus")]
    public function delete($id)
    {
        try {
            $alat = Alat::findOrFail($id);
            $alat->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Alat berhasil dihapus',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alat tidak ditemukan'
            ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus alat',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
