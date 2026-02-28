<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
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
}
