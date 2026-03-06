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
        $user = auth()->user();
        $isPeminjam = !$user->can('dashboard.view_all');

        $totalAlat = Alat::count();
        $alatDipinjam = Alat::where('status', 'dipinjam')->count();
        $alatTersedia = Alat::where('status', 'tersedia')->count();
        $alatMaintenance = Alat::where('status', 'maintenance')->count();
        $totalPeminjaman = Peminjaman::count();

        $peminjamanActivity = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Peminjaman::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $peminjamanActivity[] = [
                'name' => $date->format('M'),
                'total' => $count,
                'full_date' => $date->format('Y-m')
            ];
        }

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
