<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Get(
        path: "/api/user",
        summary: "Ambil data user yang sedang login",
        security: [["bearerAuth" => []]],
        tags: ["Auth"]
    )]
    #[OA\Response(
        response: 200,
        description: "Berhasil mengambil data user"
    )]
    public function getUser(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'no_hp' => $user->no_hp,
            'bio_singkat_ajasih' => $user->bio_singkat_ajasih,
            'jenis_kelamin' => $user->jenis_kelamin,
            'status' => $user->status,
            'foto' => $user->foto,
            'role' => $user->getRoleNames()->first(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'no_hp' => 'nullable',
            'bio_singkat_ajasih' => 'nullable',
            'jenis_kelamin' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($user->foto && !str_starts_with($user->foto, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto);
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
            'message' => 'Profil berhasil diperbarui',
            'data' => $user
        ]);
    }
}
