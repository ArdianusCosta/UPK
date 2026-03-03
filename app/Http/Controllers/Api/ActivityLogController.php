<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use OpenApi\Attributes as OA;

class ActivityLogController extends Controller
{
    #[OA\Get(
        path: "/api/activity-logs",
        summary: "Ambil daftar log aktivitas",
        security: [["bearerAuth" => []]],
        tags: ["Activity Log"]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil daftar log aktivitas"
    )]
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        
        $activities = Activity::with(['causer', 'subject'])
            ->latest()
            ->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => $activities
        ]);
    }
}
