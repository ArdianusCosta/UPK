<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
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
