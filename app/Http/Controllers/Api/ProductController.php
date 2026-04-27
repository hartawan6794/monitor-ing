<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use Validator;

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
            )->where('product.isactive', 1)
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
     * @OA\Post(
     *     path="/products",
     *     summary="Tambah Produk Baru",
     *     tags={"Product"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id", "name", "productgroup", "defunit", "salesprice1"},
     *             @OA\Property(property="id", type="string", example="SKU001"),
     *             @OA\Property(property="name", type="string", example="Nama Produk"),
     *             @OA\Property(property="productgroup", type="string", example="6197"),
     *             @OA\Property(property="defunit", type="string", example="PCS"),
     *             @OA\Property(property="salesprice1", type="number", example=100000),
     *             @OA\Property(property="initial_stock", type="number", example=10),
     *             @OA\Property(property="departement_id", type="string", example="001101"),
     *             @OA\Property(property="division_id", type="string", example="0011"),
     *             @OA\Property(property="usercreate", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produk berhasil dibuat.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Produk berhasil dibuat."),
     *             @OA\Property(property="id", type="string", example="SKU001")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'id' => 'required|string|max:30|unique:product,id',
            'aliasid' => 'nullable|string|max:30',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:200',
            'productgroup' => 'required|string|exists:productgroup,id',
            'defunit' => 'required|string|exists:units,unit',
            'groupunit' => 'nullable|string',
            'supplier' => 'nullable|string|exists:supplier,id',
            'category' => 'nullable|integer', // 0=Inventory, 1=Service
            'factory' => 'nullable|string|exists:factories,id',
            'brand' => 'nullable|string|exists:productbrand,id',
            'costprice' => 'nullable|numeric|min:0',
            'salesprice1' => 'required|numeric|min:0',
            'salesprice2' => 'nullable|numeric|min:0',
            'salesprice3' => 'nullable|numeric|min:0',
            'salesprice4' => 'nullable|numeric|min:0',
            'salesprice5' => 'nullable|numeric|min:0',
            'salesprice6' => 'nullable|numeric|min:0',
            'salesprice7' => 'nullable|numeric|min:0',
            'minimum' => 'nullable|numeric|min:0',
            'maximum' => 'nullable|numeric|min:0',
            'author' => 'nullable|string',
            'taxtype' => 'nullable|integer',
            'usercreate' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 400);
        }

        DB::beginTransaction();

        try {
            $user = $request->usercreate ?? 'admin';
            $now = now();

            // 2. Insert ke Tabel Product (Sesuai DDL Lengkap)
            DB::table('product')->insert([
                'id' => $request->id,
                'aliasid' => $request->aliasid ?? '',
                'name' => $request->name,
                'description' => $request->description ?? '',
                'productgroup' => $request->productgroup,
                'defunit' => $request->defunit,
                'groupunit' => $request->defunit,
                'supplier' => $request->supplier ?? '001001',
                'category' => $request->category ?? 0,
                'factory' => $request->factory ?? 'P1',
                'brand' => $request->brand ?? '5487',
                'costprice' => $request->costprice ?? 0,
                'purchasedisc' => 0,
                'purchasetax' => 0,
                'netpurchase' => $request->costprice ?? 0,
                'salesprice1' => $request->salesprice1 ?? 0,
                'salesprice2' => $request->salesprice2 ?? 0,
                'salesprice3' => $request->salesprice3 ?? 0,
                'salesprice4' => $request->salesprice4 ?? 0,
                'salesprice5' => $request->salesprice5 ?? 0,
                'salesprice6' => $request->salesprice6 ?? 0,
                'salesprice7' => $request->salesprice7 ?? 0,
                'salesdiscqty1' => 0,
                'salesdiscprice1' => 0,
                'salesdiscqty2' => 0,
                'salesdiscprice2' => 0,
                'salesdiscqty3' => 0,
                'salesdiscprice3' => 0,
                'usesn' => 1,
                'minimum' => $request->minimum ?? 0,
                'maximum' => $request->maximum ?? 0,
                'minimumreorder' => 0,
                'defaultreorder' => 0,
                'salesdisc' => '',
                'taxtype' => 0,
                'author' => 'p1',
                'dwidth' => 0,
                'dheight' => 0,
                'dlength' => 0,
                'weight' => 0,
                'salesdiscrules' => '',
                'salesmancommrules' => '',
                'salesproductrewardrules' => '',
                'salespointrewardrules' => '',
                'servicedoercommrules' => '',
                'inventoryacc' => '107.001',
                'taxacc' => '22.002',
                'cogsacc' => '501.001',
                'salesacc' => '401.001',
                'salesdiscacc' => '401.003',
                'salesreturnacc' => '401.002',
                'consignrevenueacc' => '401.006',
                'consignexpenseacc' => '601.001',
                'isactive' => 1,
                'usercreate' => $user,
                'useredit' => '',
                'updatetimestamp' => $now,
                'image' => null
            ]);

            // 3. Insert ke Tabel Inventory jika ada stok awal > 0
            // if ($request->initial_stock > 0) {
            //     $transId = 'OP-' . $request->id;
            //     $dateRef = date('Ymd') . mt_rand(1000, 9999);

            //     DB::table('inventory')->insert([
            //         'transid' => $transId,
            //         'transdate' => $now,
            //         'departement' => $request->departement_id,
            //         'division' => $request->division_id,
            //         'supplier' => $request->supplier ?? '001001',
            //         'productid' => $request->id,
            //         'snproduct' => '',
            //         'invin' => $request->initial_stock,
            //         'invout' => 0,
            //         'invvalue' => $request->costprice ?? 0,
            //         'reference' => 'Initial Stock',
            //         'datereference' => $dateRef,
            //         'transtype' => 6, // Penyesuaian Masuk
            //         'memo' => 'Stok Awal Produk Baru: ' . $request->id,
            //         'usercreate' => $user,
            //         'useredit' => '',
            //         'isempty' => 0
            //     ]);
            // }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Produk berhasil dibuat.',
                'id' => $request->id
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error create product: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat produk: ' . $e->getMessage()
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