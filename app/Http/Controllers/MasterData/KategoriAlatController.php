<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\MDKategoriAlat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriAlatController extends Controller
{
    public function index()
    {
        $kategoriAlats = MDKategoriAlat::all();

        return response()->json([
            'status' => 'success',
            'data' => $kategoriAlats,
        ], 200);
    }

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
                'message' => 'Terjadi kesalahan saat menambahkan Master Data Kategori Alat',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try{
            $validated = $request->validate([
                'nama_kategori_alat' => 'required|string|max:255',
                'status' => 'nullable|string|in:active,inactive',
            ]);

            DB::beginTransaction();

            $kategoriAlat = MDKategoriAlat::findOrFail($id);
            $kategoriAlat->update($validated);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Master Data Kategori Alat Berhasil Diperbarui',
                'data' => $kategoriAlat,
            ], 200);
        }catch (\Throwable $e){

            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui Master Data Kategori Alat',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function delete($id)
    {
        try{
            DB::beginTransaction();

            $kategoriAlat = MDKategoriAlat::findOrFail($id);
            $kategoriAlat->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Master Data Kategori Alat Berhasil Dihapus',
            ], 200);
        }catch (\Throwable $e){

            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus Master Data Kategori Alat',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function alatsByKategori($id)
    {
        try {
            $kategori = MDKategoriAlat::with('alats')->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $kategori->alats,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil alat berdasarkan kategori',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
