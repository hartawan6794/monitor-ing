<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuthorizedServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ConnectionController extends Controller
{
    /**
     * GET /api/connections
     * Mengembalikan daftar semua server aktif beserta database yang terdaftar.
     * Endpoint ini membaca dari database lokal (monitor-ing), bukan klien.
     */
    public function index()
    {
        $servers = AuthorizedServer::with(['availableDatabases' => function ($q) {
            $q->whereNull('deleted_at')
              ->select('id', 'server_id', 'db_name', 'description', 'expired_at')
              ->orderBy('db_name');
        }])
        ->where('is_active', 1)
        ->select('id', 'server_name', 'ip_address', 'port', 'username', 'password')
        ->orderBy('server_name')
        ->get();

        $result = $servers->map(function ($server) {
            return [
                'server_id'   => $server->id,
                'server_name' => $server->server_name,
                'ip_address'  => $server->ip_address,
                'port'        => $server->port,
                'username'    => $server->username,
                'password'    => $server->password,   // kirim credential terenkripsi ke frontend
                'databases'   => $server->availableDatabases->map(fn($db) => [
                    'id'          => $db->id,
                    'db_name'     => $db->db_name,
                    'description' => $db->description,
                    'expired_at'  => $db->expired_at,
                ]),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $result,
        ]);
    }

    /**
     * POST /api/connections/test
     * Test koneksi langsung ke server klien.
     * Body: { server_id, db_name }
     */
    public function test(Request $request)
    {
        $request->validate([
            'server_id' => 'required|integer|exists:authorized_servers,id',
            'db_name'   => 'required|string',
        ]);

        $server = AuthorizedServer::findOrFail($request->server_id);

        try {
            Config::set('database.connections.mysql_probe.driver',   'mysql');
            Config::set('database.connections.mysql_probe.host',     $server->ip_address);
            Config::set('database.connections.mysql_probe.port',     $server->port ?? 3306);
            Config::set('database.connections.mysql_probe.database', $request->db_name);
            Config::set('database.connections.mysql_probe.username', $server->username);
            Config::set('database.connections.mysql_probe.password', $server->password);
            Config::set('database.connections.mysql_probe.charset',  'utf8');

            DB::purge('mysql_probe');
            DB::connection('mysql_probe')->getPdo(); // trigger koneksi

            return response()->json([
                'status'  => 'success',
                'message' => 'Koneksi berhasil ke ' . $request->db_name . ' di ' . $server->ip_address,
                'details' => [
                    'server'    => $server->server_name,
                    'ip'        => $server->ip_address,
                    'port'      => $server->port ?? 3306,
                    'database'  => $request->db_name,
                    'username'  => $server->username,
                    'password'  => $server->password,
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
