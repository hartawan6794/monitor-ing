<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class AuthController extends Controller
{
    // Fungsi XOR Decrypt yang sama dengan Python
    private function decryptXor($hex_str, $key = "Innaddiina ")
    {
        try {
            $data = hex2bin($hex_str);
            $result = "";
            $keyLen = strlen($key);
            for ($i = 0; $i < strlen($data); $i++) {
                $b = ord($data[$i]);
                $result .= chr($b ^ ord($key[$i % $keyLen]));
            }
            return $result;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'id' => 'nullable',
            'userpassword' => 'nullable'
        ]);

        // LANGSUNG QUERY TANPA MODEL
        // DB::table akan menggunakan koneksi 'mysql' yang sudah di-reconnect oleh Middleware
        $user = DB::table('users')->where('id', $request->id)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan'], 404);
        }

        // Verifikasi Password XOR
        $decryptedPassword = $this->decryptXor($user->userpassword);

        if ($request->userpassword !== $decryptedPassword) {
            return response()->json(['status' => 'error', 'message' => 'Password salah'], 401);
        }

        // Karena tidak pakai Model, kita tidak bisa pakai $user->createToken() bawaan Sanctum.
        // Solusinya: Gunakan Query Builder untuk simpan token atau kirim respon sukses saja 
        // (Atau tetap gunakan Model hanya untuk generate token jika Sanctum aktif)

        return response()->json([
            'status' => 'success',
            'message' => 'Login Berhasil',
            'data' => [
                'user_id' => $user->id,
                'name' => $user->name,
                'username' => $user->id,
                // Kirim token dummy jika belum setup Sanctum table di DB Cabang
                'token' => bin2hex(random_bytes(20))
            ]
        ]);
    }
}
