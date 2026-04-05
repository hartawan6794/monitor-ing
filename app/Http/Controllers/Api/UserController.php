<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            'name' => 'required|string|max:10', // DB: varchar(10)
            'userpassword' => 'required|string|min:6|max:41', // DB: char(41)
            'description' => 'required|string|max:200' // DB: varchar(200) - NO NULL
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
            // Catatan: Semua kolom NOT NULL harus diisi
            $user = \App\Models\BranchUser::create([
                'id' => $request->id,
                'name' => $request->name,
                'userpassword' => encryptXor($request->userpassword),
                'description' => $request->description,
                'usercreate' => 'API', // Identitas pembuat
                'isactive' => 1,      // Aktifkan user
                'useredit' => ''       // Diisi string kosong untuk awal
            ]);

            // 3. Kembalikan Response Sukses ke Android
            return response()->json([
                'status' => 'success',
                'message' => "User {$request->name} berhasil didaftarkan."
            ], 201);

        } catch (\Exception $e) {
            // Tangkap error jika database bermasalah (misal koneksi putus)
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }
}