<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuthorizedServer;
use Log;

class DynamicConnectionController extends Controller
{

    /**
     * @OA\Post(
     *     path="/server/check",
     *     tags={"Dynamic Connection"},
     *     summary="Cek Server",
     *     description="Memeriksa apakah server terdaftar dan memiliki database aktif.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "password"},
     *             @OA\Property(property="username", type="string", example="admin"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Server valid",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="server_name", type="string", example="Server 1"),
     *                 @OA\Property(property="ip_address", type="string", example="[IP_ADDRESS]"),
     *                 @OA\Property(property="total_db", type="integer", example=2),
     *                 @OA\Property(property="databases", type="object",
     *                     @OA\Property(property="db1", type="string", example="Database 1"),
     *                     @OA\Property(property="db2", type="string", example="Database 2")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Server tidak ditemukan atau tidak aktif")
     * )
     */
    public function checkServer(Request $request)
    {
        // 1. Validasi input dari Android
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        // 2. Cari User berdasarkan username
        $user = \App\Models\User::where('username', $request->username)->first();

        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username atau password salah.',
            ], 401);
        }

        // 3. Cari langganan aktif milik user dari tabel central subscriptions
        $activeSubscription = \App\Models\Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();

        if (!$activeSubscription) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada paket langganan yang aktif untuk user ini.',
            ], 404);
        }

        // 4. Cari database yang dimiliki user
        $availableDatabases = \App\Models\AvailableDatabase::with('server')
            ->where('user_id', $user->id)
            ->get();

        // 5. Jika User tidak memiliki database yang terdaftar
        if ($availableDatabases->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada database yang dikaitkan untuk user ini.'
            ], 404);
        }

        // 5. Hapus key lama milik user ini dari device yang sama (cleanup)
        if ($request->has('device_id') && !empty($request->device_id)) {
            \App\Models\DatabaseAccessKey::where('user_id', $user->id)
                ->where('device_id', $request->device_id)
                ->delete();
        }

        // Tetap bersihkan sampah key yang sudah expired (dari device mana pun)
        \App\Models\DatabaseAccessKey::where('user_id', $user->id)
            ->where('expires_at', '<', now())
            ->delete();

        // 6. Buat Access Key baru per database
        $dbList = $availableDatabases->map(function ($db) use ($user, $request, $activeSubscription) {
            // Buat key baru
            $accessKey = bin2hex(random_bytes(32)); // 64 karakter hex
            \App\Models\DatabaseAccessKey::create([
                'user_id' => $user->id,
                'available_database_id' => $db->id,
                'access_key' => $accessKey,
                'device_id' => $request->device_id, // Simpan device_id
                'expires_at' => now()->addMonths(1),
            ]);

            // Ambil data paket dari tabel subscription (Single Source of Truth)
            $planName = $activeSubscription->pricingPlan ? strtolower($activeSubscription->pricingPlan->name) : 'basic';

            return [
                'db_name' => $db->db_name,
                'description' => $db->description,
                'package_type' => $planName,
                'expired_at' => $activeSubscription->expires_at ? $activeSubscription->expires_at->toDateString() : null, // 📅 Sama dengan global
                'access_key' => $accessKey, // 🔑 Kirim ke Android untuk dipakai saat /login
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Sukses mengambil data.',
            'data' => [
                'user' => $user->name,
                'expired_at' => $activeSubscription->expires_at ? $activeSubscription->expires_at->toDateString() : null, // 📅 Masa aktif global langganan
                'total_db' => $dbList->count(),
                'databases' => $dbList
            ]
        ], 200);
    }
}
