<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Masukkan Server Lokal
        $serverId = DB::table('authorized_servers')->insertGetId([
            'ip_address' => '127.0.0.1',
            'server_name' => 'Local Development',
            'username' => 'root',
            'password' => '',
            'port' => '3306',
            'created_at' => now(),
        ]);

        // 2. Masukkan Daftar Database untuk Server Lokal tersebut
        DB::table('available_databases')->insert([
            [
                'server_id' => $serverId,
                'db_name' => 'db_cabang_a',
                'description' => 'Database Toko Cabang A',
            ],
            [
                'server_id' => $serverId,
                'db_name' => 'db_cabang_b',
                'description' => 'Database Toko Cabang B',
            ],
        ]);
    }
}
