<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $users = \App\Models\BranchUser::all();
        return response()->json(['status' => 'success', 'data' => $users]);
    }

    /**
     * API POST: Tambah User Baru
     */
    public function store(Request $request)
    {
        // 1. Validasi Input dari Android
        $validator = Validator::make($request->all(), [
            'id' => 'required|string|unique:mysql.users,id|max:16', // DB: char(16)
            'name' => 'required|string', // DB: varchar(10)
            'userpassword' => 'required|string|max:41', // DB: char(41)
            'description' => 'required|string', // DB: varchar(200) - NO NULL
            'role' => 'nullable|string|in:kasir,sales,gudang,owner', // Tambahan Role
            'usercreate' => 'nullable|string'
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal, silakan cek kembali data Anda.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 2. Simpan ke Database menggunakan Model BranchUser
            $user = \App\Models\BranchUser::create([
                'id' => $request->id,
                'name' => $request->name,
                'userpassword' => encryptXor($request->userpassword),
                'description' => $request->description,
                'usercreate' => $request->usercreate,
                'isactive' => 1,
                'useredit' => $request->usercreate
            ]);

            // Ambil semua ID dari tabel userconfigrules
            $rules = \DB::table('userconfigrules')->select('id')->get();

            // Tentukan ID rule mana yang harus di-set 'true' berdasarkan role yang dipilih
            $trueRules = [];
            $role = $request->role;
            if ($role === 'sales') {
                $trueRules[] = '051001';
            } elseif ($role === 'kasir') {
                $trueRules[] = '053025';
            } elseif ($role === 'gudang') {
                $trueRules[] = '001002';
            } elseif ($role === 'owner') {
                // Owner mungkin butuh lebih banyak akses, tapi minimal kita set sesuai contoh
                $trueRules[] = '103001';
                $trueRules[] = '103002';
            }

            // 3. Siapkan data untuk Batch Insert ke usersconfig
            $configData = [];
            foreach ($rules as $rule) {
                // Jika ID rule cocok dengan role, set 'true', sisanya 'false'
                $value = in_array($rule->id, $trueRules) ? 'true' : 'false';

                $configData[] = [
                    'userid' => $request->id,
                    'userconfigrulesid' => $rule->id,
                    'configvalues' => $value,
                ];
            }

            // 4. Lakukan Batch Insert jika data rules tidak kosong
            if (!empty($configData)) {
                \DB::table('usersconfig')->insert($configData);
            }

            // 5. Kembalikan Response Sukses ke Android
            return response()->json([
                'status' => 'success',
                'message' => "User {$request->name} dan konfigurasi dasar berhasil didaftarkan."
            ], 201);

        } catch (\Exception $e) {
            // Tangkap error jika database bermasalah (misal koneksi putus)
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    // Fungsi untuk mendapatkan data berdasarkan userid dan section
    public function getUserConfig(Request $request)
    {
        $userid = $request->query('userid'); // Mendapatkan parameter 'userid' dari request
        $section = $request->query('section'); // Mendapatkan parameter 'section' dari request

        // Validasi input
        if (!$userid || !$section) {
            return response()->json(['error' => 'Userid and section are required'], 400);
        }

        // Query untuk mengambil data yang sesuai dengan parameter
        $result = DB::table('usersconfig as uc')
            ->join('userconfigrules as ucf', 'ucf.id', '=', 'uc.userconfigrulesid')
            ->select('ucf.id', 'ucf.description', 'uc.configvalues', 'ucf.valuetype')
            ->where('ucf.section', '=', $section)
            ->where('uc.userid', '=', $userid)
            ->get();

        // Jika tidak ada data ditemukan
        if ($result->isEmpty()) {
            return response()->json(['message' => 'No data found'], 404);
        }

        // Mengembalikan data dalam format JSON
        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function updateBulkConfig(Request $request)
    {
        // 1. Validasi Input dari Android
        $request->validate([
            'user_id' => 'required|string',
            'configs' => 'required|array',
            'configs.*.id' => 'required|string', // Ini adalah userconfigrulesid
            'configs.*.value' => 'required|string', // Ini adalah configvalues
        ]);

        $userId = $request->user_id;
        $configs = $request->configs;

        // 2. Mulai Transaksi Database
        DB::beginTransaction();

        try {
            foreach ($configs as $config) {
                // 3. Gunakan updateOrInsert (Upsert)
                // Cocokkan kombinasi userid dan aturan ID-nya
                DB::table('usersconfig')->updateOrInsert(
                    [
                        'userid' => $userId,
                        'userconfigrulesid' => $config['id'],
                    ],
                    [
                        // Jika sudah ada, update nilainya. Jika belum, insert baru.
                        'configvalues' => $config['value']
                    ]
                );
            }

            // 4. Jika semua berhasil, simpan permanen ke database
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => count($configs) . ' pengaturan berhasil diperbarui.'
            ], 200);

        } catch (Exception $e) {
            // 5. Batalkan jika ada error
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan pengaturan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API POST: Ubah Password User
     */
    public function updatePassword(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'id' => 'required|string', // ID user yang akan diubah
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // 2. Cari user berdasarkan ID
            $user = \App\Models\BranchUser::find($request->id);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            // 3. Verifikasi password lama (menggunakan encryptXor sesuai format aplikasi)
            if ($user->userpassword !== encryptXor($request->old_password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password lama yang dimasukkan salah'
                ], 400);
            }

            // 4. Update dengan password baru
            $user->update([
                'userpassword' => encryptXor($request->new_password)
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password berhasil diubah'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}