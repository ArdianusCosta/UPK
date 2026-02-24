<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class DashboardController extends Controller
{
    #[OA\Get(
        path: "/api/dashboard/stats",
        summary: "Ambil statistik untuk dashboard",
        security: [["bearerAuth" => []]],
        tags: ["Dashboard"]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil statistik dashboard"
    )]
    public function getStats()
    {
        // 1. Summary Stats
        $totalAlat = Alat::count();
        $alatDipinjam = Alat::where('status', 'dipinjam')->count();
        $alatTersedia = Alat::where('status', 'tersedia')->count();
        $alatMaintenance = Alat::where('status', 'maintenance')->count();
        $totalPeminjaman = Peminjaman::count();

        // 2. Peminjaman Activity (last 7 days)
        $peminjamanActivity = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Peminjaman::whereDate('created_at', $date->toDateString())->count();
            $peminjamanActivity[] = [
                'name' => $date->format('D'),
                'total' => $count,
                'full_date' => $date->toDateString()
            ];
        }

        // 3. Pengembalian Distribution by Category
        $pengembalianDistribution = DB::table('pengembalians')
            ->join('peminjamen', 'pengembalians.peminjaman_id', '=', 'peminjamen.id')
            ->join('alats', 'peminjamen.alat_id', '=', 'alats.id')
            ->join('m_d_kategori_alats', 'alats.kategori_alat_id', '=', 'm_d_kategori_alats.id')
            ->select('m_d_kategori_alats.nama_kategori_alat as name', DB::raw('count(*) as total'))
            ->groupBy('m_d_kategori_alats.nama_kategori_alat')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'total_alat' => $totalAlat,
                    'alat_dipinjam' => $alatDipinjam,
                    'alat_tersedia' => $alatTersedia,
                    'alat_maintenance' => $alatMaintenance,
                    'total_peminjaman' => $totalPeminjaman,
                ],
                'peminjaman_activity' => $peminjamanActivity,
                'pengembalian_distribution' => $pengembalianDistribution
            ]
        ]);
    }
}
