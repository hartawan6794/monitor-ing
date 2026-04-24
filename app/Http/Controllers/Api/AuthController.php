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

        // Ambil konfigurasi (hak akses) user dari tabel usersconfig
        $userConfigs = \Illuminate\Support\Facades\DB::table('usersconfig')
            ->where('userid', $user->id)
            ->whereIn('configvalues', ['True', 'true', '1'])
            ->pluck('userconfigrulesid')
            ->toArray();

        // Ambil SEMUA nilai konfigurasi user (termasuk Default Pelanggan, dll)
        $allUserConfigs = \Illuminate\Support\Facades\DB::table('usersconfig as uc')
            ->join('userconfigrules as ucf', 'ucf.id', '=', 'uc.userconfigrulesid')
            ->select('ucf.id', 'ucf.section', 'ucf.description', 'uc.configvalues', 'ucf.valuetype')
            ->where('uc.userid', $user->id)
            ->get()
            ->groupBy('section')
            ->map(function ($items) {
                // Ubah setiap section menjadi key-value yang mudah dibaca Android
                return $items->mapWithKeys(function ($item) {
                    return [$item->id => [
                        'description' => $item->description,
                        'value' => $item->configvalues,
                        'valuetype' => $item->valuetype, // 0=boolean, 1=string/id
                    ]];
                });
            });

        // Shortcut untuk default pelanggan (agar Android mudah mengaksesnya)
        $defaultCustomer = \Illuminate\Support\Facades\DB::table('usersconfig')
            ->where('userid', $user->id)
            ->where('userconfigrulesid', '023002')
            ->value('configvalues');

        // 1. Tentukan Role Utama (Pseudo-Role) untuk Navigasi Android
        $role = 'kasir'; // Default fallback
        if ($user->id === 'admin') {
            $role = 'admin';
        } elseif (in_array('051001', $userConfigs)) {
            $role = 'sales';
        } elseif (in_array('053025', $userConfigs)) {
            // Jika punya akses "Menu Transaksi Penjualan"
            $role = 'kasir';
        } elseif (in_array('020001', $userConfigs)) {
            // Contoh: Jika punya akses menu tertentu untuk gudang
            $role = 'gudang';
        }

        // 2. Mapping Toggles (Features) untuk Visibilitas UI di Android
        $features = [
            'can_sales' => in_array('053025', $userConfigs),
            'can_sales_order' => in_array('053025', $userConfigs),
            'is_manager' => in_array('999999', $userConfigs) // Id rule fiktif untuk contoh
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Login Berhasil',
            'data' => [
                'user_id' => $user->id,
                'name' => $user->name,
                'username' => $user->id,
                'role' => $role,
                'features' => $features,
                'default_customer' => $defaultCustomer ?? null, // Shortcut langsung
                'user_configs' => $allUserConfigs,              // Semua konfigurasi per section
                'token' => $token
            ]
        ]);
    }
}
