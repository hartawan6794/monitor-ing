<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'id' => 'nullable',
            'userpassword' => 'nullable'
        ]);

        // MENGGUNAKAN MODEL BRANCHUSER UNTUK MOBILE API
        // Koneksi 'mysql' sudah di-reconnect oleh Middleware
        $user = \App\Models\BranchUser::where('id', $request->id)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan'], 404);
        }

        // Verifikasi Password XOR (Menggunakan Global Helper)
        $decryptedPassword = decryptXor($user->userpassword);

        if ($request->userpassword !== $decryptedPassword) {
            return response()->json(['status' => 'error', 'message' => 'Password salah'], 401);
        }

        // Buat Token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login Berhasil',
            'data' => [
                'user_id' => $user->id,
                'name' => $user->name,
                'username' => $user->id,
                'token' => $token
            ]
        ]);
    }
}
