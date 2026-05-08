<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuthorizedServer;
use App\Models\AvailableDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class SetupWizardController extends Controller
{
    public function index()
    {
        return view('setup.wizard');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'status' => 'success',
            'user_id' => $user->id,
            'message' => 'User berhasil dibuat!'
        ]);
    }

    public function storeServer(Request $request)
    {
        $validated = $request->validate([
            'server_name' => 'required|string|max:255',
            'ip_address' => 'required|string|max:45',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'port' => 'nullable|integer',
        ]);

        $server = AuthorizedServer::create([
            'server_name' => $validated['server_name'],
            'ip_address' => $validated['ip_address'],
            'username' => $validated['username'],
            'password' => $validated['password'],
            'port' => $validated['port'] ?? 3306,
            'is_active' => 1,
        ]);

        // Attempt to fetch databases immediately
        try {
            Config::set('database.connections.mysql_temp.driver', 'mysql');
            Config::set('database.connections.mysql_temp.host', $server->ip_address);
            Config::set('database.connections.mysql_temp.port', $server->port);
            Config::set('database.connections.mysql_temp.username', $server->username);
            Config::set('database.connections.mysql_temp.password', $server->password);
            Config::set('database.connections.mysql_temp.database', 'information_schema');
            Config::set('database.connections.mysql_temp.charset', 'utf8');
            Config::set('database.connections.mysql_temp.collation', 'utf8_general_ci');

            DB::purge('mysql_temp');
            $databases = DB::connection('mysql_temp')->select('SHOW DATABASES');

            $excludedDbs = ['information_schema', 'mysql', 'performance_schema', 'sys', 'phpmyadmin'];
            $dbList = [];

            foreach ($databases as $db) {
                $dbName = $db->Database ?? $db->{'Database'};
                if (!in_array($dbName, $excludedDbs)) {
                    $dbList[] = $dbName;
                }
            }

            return response()->json([
                'status' => 'success',
                'server_id' => $server->id,
                'databases' => $dbList,
                'message' => 'Server berhasil ditambahkan dan koneksi sukses!'
            ]);

        } catch (\Exception $e) {
            // Rollback server creation if connection fails
            $server->delete();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal terkoneksi ke server. Pastikan IP dan Kredensial benar. Detail: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeDatabase(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'server_id' => 'required|exists:authorized_servers,id',
            'db_name' => 'required|string|max:255',
            'package_type' => 'required|string',
            'description' => 'nullable|string',
            'expired_at' => 'nullable|date',
        ]);

        // Check if DB already assigned to this server
        $existingDb = AvailableDatabase::where('server_id', $validated['server_id'])
            ->where('db_name', $validated['db_name'])
            ->first();

        if ($existingDb) {
             return response()->json([
                'status' => 'error',
                'message' => 'Database ini sudah terdaftar di sistem!'
            ], 422);
        }

        AvailableDatabase::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Setup Klien Selesai! Database berhasil dikaitkan ke user.'
        ]);
    }
}
