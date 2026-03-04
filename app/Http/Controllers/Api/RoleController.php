<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Role Management",
    description: "API untuk mengelola data role dan permission"
)]
class RoleController extends Controller
{
    #[OA\Get(
        path: "/api/roles",
        summary: "Ambil semua data role",
        security: [["bearerAuth" => []]],
        tags: ["Role Management"]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil data role"
    )]
    public function index()
    {
        $roles = Role::with('permissions')->get()->map(function($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description ?? '',
                'permissions' => $role->permissions->pluck('name')->toArray(),
                'users_count' => $role->users()->count()
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $roles
        ]);
    }

    #[OA\Post(
        path: "/api/roles",
        summary: "Tambah role baru",
        security: [["bearerAuth" => []]],
        tags: ["Role Management"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Staff"),
                    new OA\Property(property: "description", type: "string", example: "Staff operasional"),
                    new OA\Property(property: "permissions", type: "array", items: new OA\Items(type: "string"))
                ]
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Role berhasil dibuat"
    )]
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|unique:roles,name',
            'description' => 'nullable',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create([
            'name' => $validate['name'],
            'description' => $validate['description'] ?? '',
            'guard_name' => 'api'
        ]);

        if (isset($validate['permissions'])) {
            $role->syncPermissions($validate['permissions']);
        }

        return response()->json([
            'status' => 'success',
            'data' => $role
        ]);
    }

    #[OA\Put(
        path: "/api/roles/{id}",
        summary: "Update data role",
        security: [["bearerAuth" => []]],
        tags: ["Role Management"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Staff Updated"),
                    new OA\Property(property: "description", type: "string", example: "Staff operasional updated"),
                    new OA\Property(property: "permissions", type: "array", items: new OA\Items(type: "string"))
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Role berhasil diupdate"
    )]
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validate = $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'description' => 'nullable',
            'permissions' => 'nullable|array'
        ]);

        $role->update([
            'name' => $validate['name'],
            'description' => $validate['description'] ?? ''
        ]);

        if (isset($validate['permissions'])) {
            $role->syncPermissions($validate['permissions']);
        }

        return response()->json([
            'status' => 'success',
            'data' => $role
        ]);
    }

    #[OA\Delete(
        path: "/api/roles/{id}",
        summary: "Hapus role",
        security: [["bearerAuth" => []]],
        tags: ["Role Management"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ]
    )]
    #[OA\Response(
        response: 200,
        description: "Role berhasil dihapus"
    )]
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        if ($role->name === 'Admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Role Admin tidak dapat dihapus'
            ], 403);
        }

        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role berhasil dihapus'
        ]);
    }
}
