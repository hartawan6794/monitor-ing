<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DatabaseSwitcher
{
    public function handle(Request $request, Closure $next)
    {
        $dbName = $request->header('X-Database-Name');
        $accessKey = $request->header('X-Database-Key');
        $token = $request->bearerToken();

        try {
            // ────────────────────────────────────────────
            // MODE 1: Pre-Auth (via access_key)
            // ────────────────────────────────────────────
            if ($accessKey) {
                // Cache hasil lookup access_key selama 5 menit (umur tiket)
                $keyRecord = Cache::remember("db_access_key_{$accessKey}", 300, function() use ($accessKey) {
                    return \App\Models\DatabaseAccessKey::with('availableDatabase.server')
                        ->where('access_key', $accessKey)
                        ->first();
                });

                if (!$keyRecord || !$keyRecord->isValid()) {
                    return response()->json(['status' => 'error', 'message' => 'Access key invalid atau expired.'], 401);
                }

                $db = $keyRecord->availableDatabase;
                $server = $db->server;
                $this->switchConnection($server->ip_address, $server->port, $db->db_name, $server->username, $server->password);
            }

            // ────────────────────────────────────────────
            // MODE 2: Post-Auth (via Token + dbName)
            // ────────────────────────────────────────────
            elseif ($dbName && $token) {
                // OPTIMASI: Cache konfigurasi server selama 1 jam (3600 detik)
                // Ini mengurangi beban Master DB secara signifikan!
                $cacheKey = "server_config_{$dbName}";
                $availableDb = Cache::remember($cacheKey, 3600, function() use ($dbName) {
                    return \App\Models\AvailableDatabase::with('server')
                        ->where('db_name', $dbName)
                        ->first();
                });

                if (!$availableDb || !$availableDb->server) {
                    return response()->json(['status' => 'error', 'message' => 'Database tidak terdaftar.'], 403);
                }

                $server = $availableDb->server;
                $this->switchConnection($server->ip_address, $server->port, $dbName, $server->username, $server->password);

                // Cek Token di Master DB (Central)
                $accessToken = \App\Models\Sanctum\PersonalAccessToken::findToken($token);

                if (!$accessToken) {
                    return response()->json(['status' => 'error', 'message' => 'Sesi berakhir.'], 401);
                }

                // Force Auth
                $user = $accessToken->tokenable;
                if ($user) {
                    Auth::guard('api')->setUser($user);
                }
            }
        } catch (\Throwable $e) {
            Log::error('[SWITCHER ERROR] ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Gagal menghubungkan database.'], 500);
        }

        return $next($request);
    }

    /**
     * Helper: Konfigurasi dan reconnect koneksi MySQL.
     */
    private function switchConnection(string $host, string $port, string $dbName, string $username, string $password): void
    {
        // Hanya switch jika konfigurasi berbeda (opsional, tapi lebih aman dump config)
        Config::set('database.connections.mysql.host', $host);
        Config::set('database.connections.mysql.port', $port ?: '3306');
        Config::set('database.connections.mysql.database', $dbName);
        Config::set('database.connections.mysql.username', $username);
        Config::set('database.connections.mysql.password', $password);

        DB::purge('mysql');
        DB::reconnect('mysql');
    }
}