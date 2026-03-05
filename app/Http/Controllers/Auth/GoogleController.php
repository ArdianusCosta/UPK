<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'foto' => $googleUser->getAvatar(),
                    'password' => null,
                    'status' => 'Aktif',
                ]);
                
                $user->assignRole('Peminjam');
            } else {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'foto' => $googleUser->getAvatar(),
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
            return redirect()->away($frontendUrl . '/auth/google/callback?token=' . $token . '&user_id=' . $user->id);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Login Google gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
