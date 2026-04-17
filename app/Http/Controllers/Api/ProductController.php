<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->query('search');
        $divisionId = $request->query('devisi');
        $departmentId = $request->query('departement');
        $limit = $request->query('limit');

        $products = DB::table('product')
            ->leftJoin('inventory', 'product.id', '=', 'inventory.productid')
            ->leftJoin('departement', 'inventory.departement', '=', 'departement.id')
            ->leftJoin('division', 'inventory.division', '=', 'division.id')
            ->select(
                'product.id as sku',
                'product.name',
                'product.salesprice1 as price1',
                'product.salesprice2 as price2',
                'product.salesprice3 as price3',
                DB::raw('COALESCE(SUM(inventory.invin) - SUM(inventory.invout), 0) as stock'),
                'departement.id as departement_id',
                'departement.name as departement_name',
                'division.id as division_id',
                'division.description as division_name',
                'product.defunit as unit'
            )
            // Mengaplikasikan filter division dan department terlebih dahulu
            ->when($divisionId, function ($query, $divisionId) {
                return $query->where('inventory.division', $divisionId);
            })
            ->when($departmentId, function ($query, $departmentId) {
                return $query->where('inventory.departement', $departmentId);
            })
            // Setelah division dan department terfilter, kita tambahkan pencarian produk
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    // Menambahkan pencarian berdasarkan nama atau id produk
                    $query->where('product.name', 'LIKE', "%{$search}%")
                        ->orWhere('product.id', 'LIKE', "%{$search}%");
                });
            })
            ->groupBy('product.id', 'product.name', 'product.salesprice1', 'product.salesprice2', 'product.salesprice3', 'departement.id', 'departement.name', 'division.id', 'division.description', 'product.defunit')
            ->when($limit, function ($query, $limit) {
                return $query->limit($limit);
            })
            ->get();

        return response()->json(['status' => 'success', 'data' => $products]);
    }

    /**
     * @OA\Get(
     *     path="/products/low-stock-alert",
     *     summary="Alert Stok Menipis (Stok <= 5)",
     *     tags={"Product"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit jumlah data",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data produk dengan stok menipis berhasil diambil.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Data produk dengan stok menipis berhasil diambil."),
     *             @OA\Property(property="count_returned", type="integer", example=5),
     *             @OA\Property(property="total_data", type="integer", example=10),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="sku", type="string", example="PROD001"),
     *                     @OA\Property(property="name", type="string", example="Produk 1"),
     *                     @OA\Property(property="price", type="number", example=10000),
     *                     @OA\Property(property="stock", type="integer", example=3)
     *                 )
     *             )
     *         )
     *     )
     * )
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

    /**
     * @OA\Put(
     *     path="/products/{id}",
     *     summary="Update Produk",
     *     tags={"Product"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="X-Database-Name",
     *         in="header",
     *         required=true,
     *         description="Nama Database",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID Produk",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Nama Produk",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         in="query",
     *         required=true,
     *         description="Harga Produk",
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produk berhasil diupdate.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Produk berhasil diupdate.")
     *         )
     *     )
     * )
     */
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