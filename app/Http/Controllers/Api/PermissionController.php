<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Permission Management",
    description: "API untuk mengelola data permission"
)]
class PermissionController extends Controller
{
    #[OA\Get(
        path: "/api/permissions",
        summary: "Ambil semua data permission",
        security: [["bearerAuth" => []]],
        tags: ["Permission Management"]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil data permission"
    )]
    public function index()
    {
        $permissions = Permission::all()->map(function($permission) {
            $parts = explode('.', $permission->name);
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'description' => $permission->description ?? '',
                'module' => (count($parts) > 1) ? ucfirst($parts[0]) : 'Sistem',
                'guard_name' => $permission->guard_name
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $permissions
        ]);
    }

    #[OA\Post(
        path: "/api/permissions",
        summary: "Tambah permission baru",
        security: [["bearerAuth" => []]],
        tags: ["Permission Management"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "alat.view"),
                    new OA\Property(property: "description", type: "string", example: "Melihat data alat")
                ]
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Permission berhasil dibuat"
    )]
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|unique:permissions,name',
            'description' => 'nullable'
        ]);

        $permission = Permission::create([
            'name' => $validate['name'],
            'description' => $validate['description'] ?? '',
            'guard_name' => 'api'
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $permission
        ]);
    }

    #[OA\Put(
        path: "/api/permissions/{id}",
        summary: "Update data permission",
        security: [["bearerAuth" => []]],
        tags: ["Permission Management"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "alat.view.updated"),
                    new OA\Property(property: "description", type: "string", example: "Melihat data alat updated")
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Permission berhasil diupdate"
    )]
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $validate = $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
            'description' => 'nullable'
        ]);

        $permission->update([
            'name' => $validate['name'],
            'description' => $validate['description'] ?? ''
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $permission
        ]);
    }

    #[OA\Delete(
        path: "/api/permissions/{id}",
        summary: "Hapus permission",
        security: [["bearerAuth" => []]],
        tags: ["Permission Management"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ]
    )]
    #[OA\Response(
        response: 200,
        description: "Permission berhasil dihapus"
    )]
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Permission berhasil dihapus'
        ]);
    }
}
