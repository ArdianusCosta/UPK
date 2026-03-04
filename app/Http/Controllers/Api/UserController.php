<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "User Management",
    description: "API untuk mengelola data user"
)]
class UserController extends Controller
{
    #[OA\Get(
        path: "/api/users",
        summary: "Ambil semua data user",
        security: [["bearerAuth" => []]],
        tags: ["User Management"]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil data user"
    )]
    public function index()
    {
        $users = User::with('roles')->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
                'role' => $user->getRoleNames()->first(),
                'roles' => $user->roles,
                'foto' => $user->foto,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    #[OA\Post(
        path: "/api/users",
        summary: "Tambah user baru",
        security: [["bearerAuth" => []]],
        tags: ["User Management"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["name", "email", "password", "role"],
                    properties: [
                        new OA\Property(property: "name", type: "string"),
                        new OA\Property(property: "email", type: "string", format: "email"),
                        new OA\Property(property: "password", type: "string", format: "password"),
                        new OA\Property(property: "no_hp", type: "string"),
                        new OA\Property(property: "status", type: "string"),
                        new OA\Property(property: "foto", type: "string", format: "binary"),
                        new OA\Property(property: "role", type: "string")
                    ]
                )
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: "User berhasil dibuat"
    )]
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'no_hp' => 'nullable',
            'status' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'role' => 'required'
        ]);

        if ($request->hasFile('foto')) {
            $validate['foto'] = $request->file('foto')->store('users', 'public');
        }

        $validate['password'] = bcrypt($validate['password']);
        
        $role = $validate['role'];
        unset($validate['role']);

        $user = User::create($validate);
        $user->assignRole($role);

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    #[OA\Post(
        path: "/api/users/{id}",
        summary: "Update data user (Gunakan POST dengan _method=PUT untuk upload file)",
        security: [["bearerAuth" => []]],
        tags: ["User Management"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "_method", type: "string", example: "PUT"),
                        new OA\Property(property: "name", type: "string"),
                        new OA\Property(property: "email", type: "string", format: "email"),
                        new OA\Property(property: "password", type: "string", format: "password"),
                        new OA\Property(property: "no_hp", type: "string"),
                        new OA\Property(property: "status", type: "string"),
                        new OA\Property(property: "foto", type: "string", format: "binary"),
                        new OA\Property(property: "role", type: "string")
                    ]
                )
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: "User berhasil diupdate"
    )]
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'no_hp' => 'nullable',
            'status' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'role' => 'nullable'
        ]);

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $validate['foto'] = $request->file('foto')->store('users', 'public');
        }

        if (empty($validate['password'])) {
            unset($validate['password']);
        } else {
            $validate['password'] = bcrypt($validate['password']);
        }

        if (isset($validate['role'])) {
            $user->syncRoles([$validate['role']]);
            unset($validate['role']);
        }

        $user->update($validate);

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    #[OA\Delete(
        path: "/api/users/{id}",
        summary: "Hapus user",
        security: [["bearerAuth" => []]],
        tags: ["User Management"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ]
    )]
    #[OA\Response(
        response: 200,
        description: "User berhasil dihapus"
    )]
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ]);
    }
}
