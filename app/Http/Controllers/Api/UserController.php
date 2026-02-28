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
        return response()->json([
            'status' => 'success',
            'data' => User::all()
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
            'bio_singkat_ajasih' => 'nullable',
            'jenis_kelamin' => 'nullable',
            'status' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        $user->update($validate);

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
}
