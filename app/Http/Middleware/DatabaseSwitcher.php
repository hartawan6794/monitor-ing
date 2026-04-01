<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class DatabaseSwitcher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil dari Header (Android) atau Session (Web)
        $dbName = $request->header('X-Database-Name') ?? session('active_db');

        if ($dbName) {
            // OPSIONAL: Cek apakah database ini terdaftar di tabel available_databases
            // Ini penting agar orang tidak bisa akses sembarang DB via header

            config(['database.connections.mysql.database' => $dbName]);

            // Purge & Reconnect
            DB::purge('mysql');
            DB::reconnect('mysql');
        } else {
            // Jika tidak ada DB terpilih, mungkin ingin lempar error atau redirect
            // return response()->json(['message' => 'Database belum dipilih'], 400);
        }

        return $next($request);
    }
}
