<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $products = DB::table('product')
            ->leftJoin('inventory', 'product.id', '=', 'inventory.productid')
            ->select(
                'product.id as sku',
                'product.name',
                'product.salesprice1 as price',
                DB::raw('COALESCE(SUM(inventory.invin) - SUM(inventory.invout), 0) as stock')
            )
            // Tambahkan pencarian LIKE di sini
            ->when($search, function ($query, $search) {
                return $query->where('product.name', 'LIKE', "%{$search}%")
                    ->orWhere('product.id', 'LIKE', "%{$search}%");
            })
            ->groupBy('product.id', 'product.name', 'product.salesprice1')
            ->limit(100)
            ->get();

        return response()->json(['status' => 'success', 'data' => $products]);
    }
    /**
     * GET API: Alert Stok Menipis (Stok <= 5)
     * URL: /api/low-stock-alert?limit=5 (Untuk Dashboard)
     * URL: /api/low-stock-alert (Untuk Lihat Semua)
     */
    public function lowStockAlert(Request $request)
    {
        try {
            // 1. Buat Query Dasar
            $query = DB::table('product')
                ->leftJoin('inventory', 'product.id', '=', 'inventory.productid')
                ->select(
                    'product.id as sku',
                    'product.name',
                    'product.salesprice1 as price',
                    // Hitung Stok
                    DB::raw('COALESCE(SUM(inventory.invin) - SUM(inventory.invout), 0) as stock')
                )
                ->where('product.isactive', 1)
                ->groupBy('product.id', 'product.name', 'product.salesprice1')
                ->havingRaw('COALESCE(SUM(inventory.invin) - SUM(inventory.invout), 0) <= 5')
                ->orderBy('stock', 'asc');

            // 2. Hitung total data aslinya sebelum di-limit (menggunakan clone agar query utama tidak terpengaruh)
            $totalCountQuery = clone $query;
            $totalCount = $totalCountQuery->get()->count();

            // 3. Cek apakah ada parameter 'limit' yang dikirim dari Android
            if ($request->has('limit') && is_numeric($request->limit)) {
                $query->limit($request->limit);
            }

            // 4. Eksekusi query untuk mendapatkan data
            $lowStockProducts = $query->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data produk dengan stok menipis berhasil diambil.',
                // count_returned: jumlah data yang saat ini dikirim (bisa 5 atau semua)
                'count_returned' => $lowStockProducts->count(),
                // total_data: total semua produk low stock di database
                'total_data' => $totalCount,
                'data' => $lowStockProducts
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric',
        ]);

        try {
            $updated = DB::table('product')
                ->where('id', $id)
                ->update([
                    'name' => $request->name,
                    'salesprice1' => $request->price,
                    'updatetimestamp' => now(),
                    'useredit' => 'admin' // Sementara hardcode atau ambil dari auth
                ]);

            if ($updated) {
                return response()->json(['status' => 'success', 'message' => 'Produk berhasil diperbarui']);
            }
            return response()->json(['status' => 'error', 'message' => 'Tidak ada perubahan atau produk tidak ditemukan'], 404);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}