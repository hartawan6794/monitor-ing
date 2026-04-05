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
     * GET API: Alert Stok Menipis (Stok <= 50)
     */
    public function lowStockAlert()
    {
        try {
            $lowStockProducts = DB::table('product')
                ->leftJoin('inventory', 'product.id', '=', 'inventory.productid')
                ->select(
                    'product.id as sku',
                    'product.name',
                    'product.salesprice1 as price',
                    // Hitung Stok
                    DB::raw('COALESCE(SUM(inventory.invin) - SUM(inventory.invout), 0) as stock')
                )
                ->groupBy('product.id', 'product.name', 'product.salesprice1')
                // Filter hanya yang stoknya 50 atau ke bawah
                ->havingRaw('COALESCE(SUM(inventory.invin) - SUM(inventory.invout), 0) <= 50')
                //untukdinamis
                // ->havingRaw('COALESCE(SUM(inventory.invin) - SUM(inventory.invout), 0) <= product.minimum')
                // Urutkan dari stok yang paling sedikit / minus
                ->orderBy('stock', 'asc')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Data produk dengan stok menipis berhasil diambil.',
                'count'  => $lowStockProducts->count(), // Membantu FE untuk menampilkan badge notifikasi
                'data'   => $lowStockProducts
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