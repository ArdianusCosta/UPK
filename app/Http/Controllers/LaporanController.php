<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Laporan",
    description: "API untuk mengelola laporan peminjaman"
)]
class LaporanController extends Controller
{
    #[OA\Get(
        path: "/api/laporan",
        summary: "Ambil data laporan peminjaman",
        security: [["bearerAuth" => []]],
        tags: ["Laporan"],
        parameters: [
            new OA\Parameter(name: "start", in: "query", schema: new OA\Schema(type: "string", format: "date")),
            new OA\Parameter(name: "end", in: "query", schema: new OA\Schema(type: "string", format: "date")),
            new OA\Parameter(name: "status", in: "query", schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "user_id", in: "query", schema: new OA\Schema(type: "string"))
        ]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil data laporan"
    )]
    public function index(Request $request)
    {
        $query = Peminjaman::with(['peminjam', 'alat.kategoriAlat']);

        if ($request->start && $request->end) {
            $query->whereBetween('tanggal_pinjam', [$request->start, $request->end]);
        }

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->user_id && $request->user_id !== 'all') {
            $query->where('peminjam_id', $request->user_id);
        }

        $peminjaman = $query->latest()->get();

        $summary = [
            'total_peminjaman' => $peminjaman->count(),
            'total_selesai' => $peminjaman->where('status', 'Dikembalikan')->count(),
            'total_terlambat' => $peminjaman->where('status', 'Terlambat')->count(),
            'total_dipinjam' => $peminjaman->where('status', 'Dipinjam')->count(),
        ];

        $charts = [
            'top_alat' => [],
            'stats_per_month' => []
        ];

        try {
            $charts['top_alat'] = DB::table('peminjamen')
                ->join('alats', 'peminjamen.alat_id', '=', 'alats.id')
                ->select('alats.nama', DB::raw('count(*) as total'))
                ->groupBy('alats.id', 'alats.nama')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();

            $charts['stats_per_month'] = DB::table('peminjamen')
                ->select(
                    DB::raw('MONTH(tanggal_pinjam) as month_num'), 
                    DB::raw('MONTHNAME(tanggal_pinjam) as bulan'), 
                    DB::raw('count(*) as total')
                )
                ->whereYear('tanggal_pinjam', date('Y'))
                ->groupBy('month_num', 'bulan')
                ->orderBy('month_num')
                ->get();
        } catch (\Exception $e) {
            \Log::error('Laporan Charts Error: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'data' => $peminjaman,
            'summary' => $summary,
            'charts' => $charts
        ]);
    }

    #[OA\Get(
        path: "/api/laporan/export/excel",
        summary: "Export laporan ke Excel",
        security: [["bearerAuth" => []]],
        tags: ["Laporan"],
        parameters: [
            new OA\Parameter(name: "start", in: "query", schema: new OA\Schema(type: "string", format: "date")),
            new OA\Parameter(name: "end", in: "query", schema: new OA\Schema(type: "string", format: "date")),
            new OA\Parameter(name: "status", in: "query", schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "user_id", in: "query", schema: new OA\Schema(type: "string"))
        ]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mendownload file Excel"
    )]
    public function exportExcel(Request $request)
    {
        $query = Peminjaman::with(['peminjam', 'alat.kategoriAlat']);

        if ($request->start && $request->end) {
            $query->whereBetween('tanggal_pinjam', [$request->start, $request->end]);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->user_id) {
            $query->where('peminjam_id', $request->user_id);
        }

        $data = $query->latest()->get();

        return Excel::download(new LaporanExport($data), 'laporan-peminjaman-' . now()->format('Y-m-d') . '.xlsx');
    }

    #[OA\Get(
        path: "/api/laporan/export/pdf",
        summary: "Export laporan ke PDF",
        security: [["bearerAuth" => []]],
        tags: ["Laporan"],
        parameters: [
            new OA\Parameter(name: "start", in: "query", schema: new OA\Schema(type: "string", format: "date")),
            new OA\Parameter(name: "end", in: "query", schema: new OA\Schema(type: "string", format: "date")),
            new OA\Parameter(name: "status", in: "query", schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "user_id", in: "query", schema: new OA\Schema(type: "string"))
        ]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mendownload file PDF"
    )]
    public function exportPdf(Request $request)
    {
        $query = Peminjaman::with(['peminjam', 'alat.kategoriAlat']);

        if ($request->start && $request->end) {
            $query->whereBetween('tanggal_pinjam', [$request->start, $request->end]);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->user_id) {
            $query->where('peminjam_id', $request->user_id);
        }

        $data = $query->latest()->get();
        
        $summary = [
            'total_peminjaman' => $data->count(),
            'total_selesai' => $data->where('status', 'Dikembalikan')->count(),
            'total_terlambat' => $data->where('status', 'Terlambat')->count(),
            'period' => ($request->start && $request->end) ? $request->start . ' s/d ' . $request->end : 'Semua Periode'
        ];

        $pdf = Pdf::loadView('laporan.pdf', compact('data', 'summary'));
        return $pdf->download('laporan-peminjaman-' . now()->format('Y-m-d') . '.pdf');
    }
}
