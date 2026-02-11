<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    public function index()
    {
        try {
            $alats = Alat::with('kategoriAlat')->get();
            return response()->json([
                'status' => 'success',
                'data' => $alats,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data alat',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kode' => 'required|string|unique:alats,kode',
                'nama' => 'required|string|max:255',
                'kategori_alat_id' => 'nullable|exists:m_d_kategori_alats,id',
                'kondisi' => 'nullable|string|in:baik,sedang,rusak',
                'deskripsi' => 'nullable|string',
                'lokasi' => 'nullable|string|max:255',
                'foto' => 'nullable|image|jpg,png,jpeg,gif,svg|max:2048',
                'stok' => 'nullable|integer|min:0',
                'status' => 'nullable|string|in:tersedia,dipinjam,maintenance',
            ]);

            $alat = Alat::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Alat berhasil ditambahkan',
                'data' => $alat,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan alat',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'kode' => 'required|string|unique:alats,kode,' . $id,
                'nama' => 'required|string|max:255',
                'kategori_alat_id' => 'nullable|exists:m_d_kategori_alats,id',
                'kondisi' => 'nullable|string|in:baik,sedang,rusak',
                'deskripsi' => 'nullable|string',
                'lokasi' => 'nullable|string|max:255',
                'foto' => 'nullable|image|jpg,png,jpeg,gif,svg|max:2048',
                'stok' => 'nullable|integer|min:0',
                'status' => 'nullable|string|in:tersedia,dipinjam,maintenance',
            ]);

            $alat = Alat::findOrFail($id);
            $alat->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Alat berhasil diperbarui',
                'data' => $alat,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui alat',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $alat = Alat::findOrFail($id);
            $alat->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Alat berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus alat',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
