<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuthorizedServer;

class DynamicConnectionController extends Controller
{
    public function checkServer(Request $request)
    {
        // 1. Validasi input dari Android
        $request->validate([
            'ip_address' => 'required|ip',
            // Jika ingin Android mengirim kredensial, aktifkan ini:
            'username' => 'nullable|string',
            'password' => 'nullable|string',
        ]);


        // 2. Cari IP di tabel AuthorizedServer (hanya yang aktif)
        $server = AuthorizedServer::with([
            'availableDatabases' => function ($query) {
                // Filter hanya database yang belum expired
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            }
        ])
            ->where('ip_address', $request->ip_address)
            // Jika ingin Android mengirim kredensial, aktifkan ini:
            ->where('username', $request->username)
            ->where(function ($query) use ($request) {
                if ($request->password != null || $request->password != '') {
                    $query->where('password', $request->password);
                }
            })
            ->where('is_active', 1)
            ->first();

        // 3. Jika Server tidak ditemukan di database pusat
        if (!$server) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server tidak terdaftar di sistem pusat atau sedang tidak aktif.',
                'data' => $request->all()
            ], 404);
        }

        // 4. Jika Server ada, tapi belum ada database yang didaftarkan
        if ($server->availableDatabases->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada database yang tersedia/aktif untuk server ini.'
            ], 404);
        }

        // 5. Susun daftar database untuk dikirim ke Android
        $dbList = $server->availableDatabases->pluck('db_name', 'description');

        return response()->json([
            'status' => 'success',
            'message' => 'Server valid.',
            'data' => [
                'server_name' => $server->server_name,
                'ip_address' => $server->ip_address,
                'total_db' => $dbList->count(),
                'databases' => $dbList
            ]
        ], 200);
    }
}
