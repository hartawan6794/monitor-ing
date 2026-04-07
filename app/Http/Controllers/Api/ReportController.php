<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function stockReport(Request $request)
    {
        // Parameter Filter dari Android
        $productId = $request->query('productid');
        $divisionId = $request->query('division');
        $supplierId = $request->query('supplier');
        $groupId = $request->query('group');
        $brandId = $request->query('brand');

        // Query Utama dengan Join yang disesuaikan Schema
        $query = DB::table('product as p')
            ->leftJoin('inventory as i', 'p.id', '=', 'i.productid')
            ->leftJoin('productgroup as pg', 'p.productgroup', '=', 'pg.id')
            ->leftJoin('supplier as s', 'p.supplier', '=', 's.id')
            ->select(
                'p.id as productid',
                'p.name as productname',
                'p.id as sku', // Menggunakan aliasid sebagai SKU
                'pg.name as groupname',
                'i.division',
                'i.departement',
                's.name as suppliername',
                DB::raw('SUM(i.invin - i.invout) as total_stock')
            );

        // Terapkan Filter
        // GANTI BAGIAN INI DI LARAVEL
        if ($productId) {
            $query->where(function ($q) use ($productId) {
                $q->where('p.id', 'LIKE', "%{$productId}%")
                    ->orWhere('p.aliasid', 'LIKE', "%{$productId}%")
                    ->orWhere('p.name', 'LIKE', "%{$productId}%");
            });
        }
        if ($divisionId)
            $query->where('i.division', $divisionId);
        if ($supplierId)
            $query->where('p.supplier', $supplierId); // Supplier biasanya menempel di master produk
        if ($groupId)
            $query->where('p.productgroup', $groupId);
        if ($brandId)
            $query->where('p.brand', $brandId);


        $data = $query->groupBy('p.id', 'p.name', 'p.aliasid', 'pg.name', 'i.division', 'i.departement')
            ->having('total_stock', '!=', 0); // Tampilkan yang stoknya tidak 0 (bisa minus atau plus)

        $hasFilters = $productId || $divisionId || $supplierId || $groupId || $brandId;

        // Jika tidak ada filter sama sekali, batasi hasil ke 100 data saja
        if (!$hasFilters) {
            $query->limit(100);
        }
        // Eksekusi Query
        $data = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function stockHistory(Request $request)
    {
        $productId = $request->query('productid');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        if (!$productId) {
            return response()->json(['status' => 'error', 'message' => 'Pilih produk terlebih dahulu'], 400);
        }

        $query = DB::table('inventory as i')
            ->join('product as p', 'i.productid', '=', 'p.id')
            ->select(
                DB::raw('DATE(i.transdate) as transdate'), // Memisahkan tanggal
                DB::raw('TIME(i.transdate) as transtime'), // Memisahkan jam
                'i.transid',
                'i.invin',
                'i.invout',
                'i.memo as description', // Menggunakan kolom memo sebagai description
                'i.departement'
            )
            ->where('i.productid', $productId);

        if ($fromDate && $toDate) {
            // Karena transdate adalah DATETIME, kita pastikan jamnya dicakup (00:00:00 s/d 23:59:59)
            $query->whereBetween('i.transdate', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        // Urutkan berdasarkan waktu transaksi murni (transdate DATETIME asli)
        $data = $query->orderBy('i.transdate', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }


    public function inOutReport(Request $request)
    {
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $deptId = $request->query('departement');
        $groupId = $request->query('group');

        $query = DB::table('inventory as i')
            ->join('product as p', 'i.productid', '=', 'p.id')
            ->leftJoin('productgroup as pg', 'p.productgroup', '=', 'pg.id')
            ->select(
                'p.id as productid',
                'p.name as productname',
                'p.id as sku',
                'pg.name as groupname',
                DB::raw('SUM(i.invin) as total_in'),
                DB::raw('SUM(i.invout) as total_out')
            );

        // Filter Rentang Tanggal (Wajib untuk performa, jika kosong kita batasi datanya)
        if ($fromDate && $toDate) {
            $query->whereBetween('i.transdate', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        // Filter Opsional (Bisa dipakai jika kamu ingin menambahkan filter lanjutan)
        if ($deptId)
            $query->where('i.departement', $deptId);
        if ($groupId)
            $query->where('p.productgroup', $groupId);

        $data = $query->groupBy('p.id', 'p.name', 'p.aliasid', 'pg.name')
            ->havingRaw('total_in > 0 OR total_out > 0') // Hanya tampilkan yang ada pergerakan
            ->orderBy('p.name', 'asc');

        // Jika tidak ada filter tanggal, limit agar tidak nge-hang
        if (!$fromDate && !$toDate) {
            $query->limit(100);
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get()
        ]);
    }

    public function transferReport(Request $request)
    {
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        // Query untuk mengambil pasangan In dan Out berdasarkan transid yang sama
        $query = DB::table('inventory as i')
            ->join('product as p', 'i.productid', '=', 'p.id')
            ->select(
                'i.transid',
                'i.transdate',
                'p.name as productname',
                // Mencari departemen yang melakukan INVOUT sebagai 'Asal'
                DB::raw("MAX(CASE WHEN i.invout > 0 THEN i.departement END) as from_dept"),
                // Mencari departemen yang melakukan INVIN sebagai 'Tujuan'
                DB::raw("MAX(CASE WHEN i.invin > 0 THEN i.departement END) as to_dept"),
                DB::raw("MAX(GREATEST(i.invin, i.invout)) as qty"),
                'i.memo'
            )
            // Biasanya mutasi memiliki kode khusus di transid, misal diawali 'TRF' atau 'MOV'
            // Jika tidak ada kode khusus, kita filter yang memiliki pasangan in/out
            ->where('i.transid', 'LIKE', 'MOV%')
            ->groupBy('i.transid', 'i.transdate', 'p.name', 'i.memo');

        if ($fromDate && $toDate) {
            $query->whereBetween('i.transdate', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        } else {
            $query->limit(100);
        }

        $data = $query->orderBy('i.transdate', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function adjustReport(Request $request)
    {
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $deptId = $request->query('departement');

        $query = DB::table('inventory as i')
            ->join('product as p', 'i.productid', '=', 'p.id')
            ->select(
                'i.transid',
                'i.transdate',
                'p.name as productname',
                'p.aliasid as sku',
                'i.departement',
                'i.invin',
                'i.invout',
                'i.memo'
            )
            // Menapis berdasarkan prefix I/IM-
            ->where('i.transid', 'LIKE', 'I/IM-%');

        if ($fromDate && $toDate) {
            $query->whereBetween('i.transdate', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        if ($deptId) {
            $query->where('i.departement', $deptId);
        }

        $data = $query->orderBy('i.transdate', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}