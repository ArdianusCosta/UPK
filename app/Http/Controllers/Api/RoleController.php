<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
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
