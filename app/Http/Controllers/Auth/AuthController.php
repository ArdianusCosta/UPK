<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
            'password' => 'nullable|min:8|confirmed',
            'password_confirmation' => 'nullable|min:8',
            'current_password' => 'required_with:password',
            'no_hp' => 'nullable',
            'bio_singkat_ajasih' => 'nullable',
            'jenis_kelamin' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password saat ini tidak sesuai',
                ], 422);
            }
            $validate['password'] = bcrypt($request->password);
        } else {
            unset($validate['password']);
        }

        unset($validate['password_confirmation']);
        unset($validate['current_password']);

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

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        $user = User::where('email', $request->email)->first();
        Log::info('Sending reset password email to: ' . $user->email);
        $user->notify(new ResetPasswordNotification($token));
        Log::info('Reset password email sent to: ' . $user->email);

        return response()->json([
            'status' => 'success',
            'message' => 'Kode reset password telah dikirim ke email Anda'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $resetData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetData) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode reset tidak valid atau sudah kadaluarsa'
            ], 422);
        }

        if (Carbon::parse($resetData->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return response()->json([
                'status' => 'error',
                'message' => 'Kode reset sudah kadaluarsa'
            ], 422);
        }

        if (!Hash::check($request->token, $resetData->token)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode reset tidak valid'
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => bcrypt($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Password berhasil direset. Silakan login dengan password baru Anda.'
        ]);
    }
}
