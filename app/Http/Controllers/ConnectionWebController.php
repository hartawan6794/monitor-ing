<?php

namespace App\Http\Controllers;

use App\Models\AuthorizedServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ConnectionWebController extends Controller
{
    /**
     * POST /connections/test
     * Test koneksi ke server klien menggunakan web session (tidak butuh API token).
     */
    public function test(Request $request)
    {
        $request->validate([
            'server_id' => 'required|integer|exists:authorized_servers,id',
            'db_name'   => 'required|string',
        ]);

        // [IDOR PATCH]: Pastikan user adalah admin, atau merupakan pemilik dari database tersebut
        $user = auth()->user();
        if (!$user->isAdmin()) {
            $isOwned = \App\Models\AvailableDatabase::where('user_id', $user->id)
                ->where('server_id', $request->server_id)
                ->where('db_name', $request->db_name)
                ->exists();

            if (!$isOwned) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Unauthorized Access (IDOR). Anda tidak memiliki hak akses ke database ini.',
                ], 403);
            }
        }

        $server = AuthorizedServer::findOrFail($request->server_id);

        try {
            Config::set('database.connections.mysql_probe.driver',   'mysql');
            Config::set('database.connections.mysql_probe.host',     $server->ip_address);
            Config::set('database.connections.mysql_probe.port',     $server->port ?? 3306);
            Config::set('database.connections.mysql_probe.database', $request->db_name);
            Config::set('database.connections.mysql_probe.username', $server->username);
            Config::set('database.connections.mysql_probe.password', $server->password);
            Config::set('database.connections.mysql_probe.charset',  'utf8');
            Config::set('database.connections.mysql_probe.collation', 'utf8_unicode_ci');

            DB::purge('mysql_probe');
            DB::connection('mysql_probe')->getPdo();

            return response()->json([
                'status'  => 'success',
                'message' => 'Koneksi berhasil ke ' . $request->db_name,
                'details' => [
                    'server'    => $server->server_name,
                    'ip'        => $server->ip_address,
                    'database'  => $request->db_name,
                    // [DATA LEAK PATCH]: Username dan Password dihapus dari respons JSON
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Koneksi gagal: ' . $e->getMessage(),
            ], 422);
        }
    }
}
