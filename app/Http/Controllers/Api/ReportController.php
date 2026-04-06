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
}