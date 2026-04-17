<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Auth"},
     *     summary="Login pengguna",
     *     description="Autentikasi pengguna menggunakan ID dan password.",
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Key"),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "userpassword"},
     *             @OA\Property(property="id", type="string", example="john"),
     *             @OA\Property(property="userpassword", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="1|abc123...")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
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
