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
    private function generateSpaCode()
    {
        $lastAlat = Alat::orderBy('id', 'desc')->first();

        if ($lastAlat && str_starts_with($lastAlat->kode, 'SPA')) {
            $lastNumber = intval(substr($lastAlat->kode, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'SPA' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    #[OA\Get(
        path: "/api/alats",
        summary: "Ambil semua alat",
        security: [["bearerAuth" => []]],
        tags: ["Alat"]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil data",
        content: new OA\JsonContent(
            example: [
                "status" => "success",
                "data" => [
                    [
                        "id" => 1,
                        "kode" => "SPA00001",
                        "nama" => "Laptop Asus",
                        "kategori_alat_id" => 1,
                        "stok" => 10,
                        "status" => "tersedia",
                        "created_at" => "2026-02-20T10:00:00.000000Z",
                        "updated_at" => "2026-02-20T10:00:00.000000Z"
                    ]
                ]
            ]
        )
    )]
    public function index()
    {
        $alats = Alat::with('kategoriAlat')->get();

        return response()->json([
            'status' => 'success',
            'data' => $alats,
        ], 200);
    }

    #[OA\Get(
        path: "/api/alats/{id}",
        summary: "Ambil detail alat",
        security: [["bearerAuth" => []]],
        tags: ["Alat"]
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil detail alat",
        content: new OA\JsonContent(
            example: [
                "status" => "success",
                "data" => [
                    "id" => 1,
                    "kode" => "SPA00001",
                    "nama" => "Laptop Asus",
                    "kategori_alat_id" => 1,
                    "stok" => 10,
                    "status" => "tersedia",
                    "created_at" => "2026-02-20T10:00:00.000000Z",
                    "updated_at" => "2026-02-20T10:00:00.000000Z"
                ]
            ]
        )
    )]
    public function show($id)
    {
        $alat = Alat::with('kategoriAlat')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $alat,
        ], 200);
    }

    #[OA\Post(
        path: "/api/alats",
        summary: "Tambah alat",
        security: [["bearerAuth" => []]],
        tags: ["Alat"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: "multipart/form-data",
            schema: new OA\Schema(
                required: ["nama"],
                properties: [
                    new OA\Property(property: "nama", type: "string", example: "Laptop Asus"),
                    new OA\Property(property: "kategori_alat_id", type: "integer", example: 1),
                    new OA\Property(property: "foto", type: "string", format: "binary"),
                    new OA\Property(property: "stok", type: "integer", example: 10),
                    new OA\Property(property: "status", type: "string", example: "tersedia")
                ]
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Berhasil menambahkan alat",
        content: new OA\JsonContent(
            example: [
                "status" => "success",
                "message" => "Alat berhasil ditambahkan",
                "data" => [
                    "id" => 1,
                    "kode" => "SPA00001",
                    "nama" => "Laptop Asus",
                    "kategori_alat_id" => 1,
                    "stok" => 10,
                    "status" => "tersedia"
                ]
            ]
        )
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_alat_id' => 'nullable|exists:m_d_kategori_alats,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'stok' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:tersedia,dipinjam,maintenance',
        ]);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('uploads/alat'), $fotoName);
            $validated['foto'] = $fotoName;
        }

        $validated['kode'] = $this->generateSpaCode();
        $validated['status'] = $validated['status'] ?? 'tersedia';

        $alat = Alat::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Alat berhasil ditambahkan',
            'data' => $alat,
        ], 201);
    }

    #[OA\Patch(
        path: "/api/alats/{id}",
        summary: "Update alat",
        security: [["bearerAuth" => []]],
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
        content: new OA\MediaType(
            mediaType: "multipart/form-data",
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: "nama", type: "string", example: "Laptop Asus Update"),
                    new OA\Property(property: "stok", type: "integer", example: 5),
                    new OA\Property(property: "status", type: "string", example: "maintenance"),
                    new OA\Property(property: "kategori_alat_id", type: "integer", example: 1),
                    new OA\Property(property: "foto", type: "string", format: "binary")
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil update alat",
        content: new OA\JsonContent(
            example: [
                "status" => "success",
                "message" => "Alat berhasil diperbarui"
            ]
        )
    )]
    public function update(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'sometimes|string|max:255',
            'kategori_alat_id' => 'nullable|exists:m_d_kategori_alats,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
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
        ], 200);
    }

    #[OA\Delete(
        path: "/api/alats/{id}",
        summary: "Hapus alat",
        security: [["bearerAuth" => []]],
        tags: ["Alat"]
    )]
    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil hapus alat",
        content: new OA\JsonContent(
            example: [
                "status" => "success",
                "message" => "Alat berhasil dihapus"
            ]
        )
    )]
    public function delete($id)
    {
        $alat = Alat::findOrFail($id);
        $alat->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Alat berhasil dihapus',
        ], 200);
    }
}