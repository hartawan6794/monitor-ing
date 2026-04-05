<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvailableDatabaseRequest; // Ganti AvailableDatabase dengan nama model
use App\Models\AvailableDatabase; // Ganti AvailableDatabase dengan nama model
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AuthorizedServer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class AvailableDatabaseController extends Controller
{
    public function index()
    {
        return view('available_database.index'); // Ganti available_database dengan nama model dalam huruf kecil
    }

    public function getData()
    {
        // 1. Ambil data dari model Server beserta relasi database-nya

        $data = AuthorizedServer::with('availableDatabases')->select('*')->where('is_active', 1);

        return DataTables::of($data)
            ->addColumn('database_list', function ($server) {
                $html = '<div class="flex flex-wrap gap-2">';
                foreach ($server->availableDatabases as $db) {
                    $html .= '<span class="inline-flex items-center bg-primary/10 text-primary text-[11px] font-medium px-2.5 py-0.5 rounded-full border border-primary/20" title="' . htmlspecialchars($db->description) . '">';
                    $html .= $db->db_name;
                    $html .= '</span>';
                }
                $html .= '</div>';
                return $html;
            })
            ->addColumn('action', function ($server) {
                // Edit tunggal mengarah ke halaman manajemen database server tersebut
                $editUrl = route('available_database.manage', $server->id);

                return '<a href="' . $editUrl . '" class="ti-btn ti-btn-sm ti-btn-info !rounded-full">
                        <i class="ri-settings-3-line mr-1"></i> Manage DB
                    </a>';
            })
            ->rawColumns(['database_list', 'action'])
            ->make(true);
    }
    public function fetchDatabasesFromServer($serverId)
    {
        try {
            $server = AuthorizedServer::findOrFail($serverId);

            // 1. Ubah koneksi template MySQL agar mengarah ke IP Server yang dipilih
            Config::set('database.connections.mysql_temp.driver', 'mysql');
            Config::set('database.connections.mysql_temp.host', $server->ip_address);
            Config::set('database.connections.mysql_temp.port', $server->port);
            // Gunakan kredensial database master/root untuk server tersebut
            // Pastikan Anda sudah mengatur user yang punya hak akses 'SHOW DATABASES'
            Config::set('database.connections.mysql_temp.username', $server->username);
            Config::set('database.connections.mysql_temp.password', $server->password);

            // Reset koneksi sementara ini
            DB::purge('mysql_temp');

            // 2. Jalankan perintah native SQL untuk membaca seluruh database
            $databases = DB::connection('mysql_temp')->select('SHOW DATABASES');

            // 3. Filter database bawaan sistem MySQL agar tidak muncul
            $excludedDbs = ['information_schema', 'mysql', 'performance_schema', 'sys', 'phpmyadmin'];
            $dbList = [];

            foreach ($databases as $db) {
                // Karena hasil 'SHOW DATABASES' mengembalikan object dengan properti 'Database'
                $dbName = $db->Database ?? $db->{'Database'};

                if (!in_array($dbName, $excludedDbs)) {
                    $dbList[] = $dbName;
                }
            }

            return response()->json(['status' => 'success', 'data' => $dbList]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal terkoneksi ke server: ' . $e->getMessage()], 500);
        }
    }

    public function store(AvailableDatabaseRequest $request)
    {
        $validated = $request->validated();
        // check if db_name already exists in server_id
        $existingDb = AvailableDatabase::where('server_id', $validated['server_id'])
            ->where('db_name', $validated['db_name'])
            ->first();
        if ($existingDb) {
            Alert::error('Error', 'Database Sudah Ada');
            return redirect()->route('available_database.manage', $validated['server_id']);
        }
        AvailableDatabase::create($validated);
        Alert::success('Data AvailableDatabase Berhasil Disimpan');
        return redirect()->route('available_database.manage', $validated['server_id']);
    }

    public function manage($serverId)
    {
        $server = AuthorizedServer::with('availableDatabases')->findOrFail($serverId);
        return view('available_database.manage', compact('server'));
    }


    public function destroy(AvailableDatabase $available_database)
    {
        // Pastikan data ini memang milik server yang sah (Optional security check)
        if (!$available_database->server_id) {
            Alert::error('Error', 'Data tidak valid');
            return back();
        }

        $serverId = $available_database->server_id;
        $available_database->delete();

        Alert::success('Data Berhasil Dihapus');
        return redirect()->route('available_database.manage', $serverId);
    }
    public function restore($id)
    {
        // Cari data yang statusnya soft-deleted
        $db = AvailableDatabase::withTrashed()->findOrFail($id);

        // Kembalikan datanya
        $db->restore();

        Alert::success('Berhasil', 'Koneksi Database Telah Dipulihkan');
        return redirect()->route('available_database.manage', $db->server_id);
    }
}
