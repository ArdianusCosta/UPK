<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
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
