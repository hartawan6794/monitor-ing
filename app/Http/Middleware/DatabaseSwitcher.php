<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseSwitcher
{
    public function handle(Request $request, Closure $next)
    {
        // TEST: Paling awal, sebelum apapun
        Log::info('[SWITCHER START] Middleware called', ['url' => $request->path()]);

        try {
            $dbName = $request->header('X-Database-Name');
            $dbHost = $request->header('X-Server-IP');
            $dbUser = $request->header('X-DB-Username');
            $dbPass = $request->header('X-DB-Password');

            Log::info('[SWITCHER HEADERS]', compact('dbName', 'dbHost', 'dbUser'));

            if ($dbName && $dbHost) {
                Config::set('database.connections.mysql.host', $dbHost);
                Config::set('database.connections.mysql.database', $dbName);
                Config::set('database.connections.mysql.username', $dbUser ?? config('database.connections.mysql.username'));
                Config::set('database.connections.mysql.password', $dbPass ?? config('database.connections.mysql.password'));

                DB::purge('mysql');
                DB::reconnect('mysql');

                $activeDb = DB::connection('mysql')->getDatabaseName();
                Log::info('[SWITCHER] Switched to DB: ' . $activeDb);

                // Verifikasi user dari token
                $token = $request->bearerToken();
                if ($token) {
                    $accessToken = \App\Models\Sanctum\PersonalAccessToken::findToken($token);
                    if ($accessToken) {
                        $userId = $accessToken->tokenable_id;
                        $userExists = DB::connection('mysql')->table('users')->where('id', $userId)->exists();
                        Log::info('[SWITCHER] User check:', [
                            'db' => $activeDb,
                            'user_id' => $userId,
                            'found' => $userExists ? 'YES' : 'NO'
                        ]);
                    } else {
                        Log::warning('[SWITCHER] Token not found in central DB');
                    }
                }
            } else {
                Log::info('[SWITCHER] No DB headers, skipping switch');
            }
        } catch (\Throwable $e) {
            Log::error('[SWITCHER ERROR] ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }

        return $next($request);
    }
}