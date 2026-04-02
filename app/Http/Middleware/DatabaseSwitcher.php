<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DatabaseSwitcher
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Tangkap SEMUA informasi koneksi dari Header Android
        $dbName = $request->header('X-Database-Name');
        $dbHost = $request->header('X-Server-IP');
        $dbUser = $request->header('X-DB-Username');
        $dbPass = $request->header('X-DB-Password');

        // Jika request memiliki header X-Database-Name, berarti ini request dinamis
        if ($dbName && $dbHost) {

            // 2. Timpa konfigurasi default MySQL secara runtime
            Config::set('database.connections.mysql.host', $dbHost);
            Config::set('database.connections.mysql.database', $dbName);
            Config::set('database.connections.mysql.username', $dbUser);
            Config::set('database.connections.mysql.password', $dbPass);

            // 3. Purge & Reconnect agar Eloquent menggunakan konfigurasi baru ini
            DB::purge('mysql');
            DB::reconnect('mysql');
        }

        return $next($request);
    }
}