<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;

class SalesController extends Controller
{

    /**
     * @OA\Get(
     *     path="/sales/history",
     *     tags={"Sales"},
     *     summary="Riwayat Penjualan (Header Faktur)",
     *     description="Mengambil daftar faktur penjualan dengan filter opsional. Total per faktur dihitung dari salesdetail.netamount.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
     *     @OA\Parameter(name="date_from", in="query", required=false, description="Tanggal mulai (YYYY-MM-DD)", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="date_to", in="query", required=false, description="Tanggal akhir (YYYY-MM-DD)", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="customer_id", in="query", required=false, description="Filter ID Customer", @OA\Schema(type="string")),
     *     @OA\Parameter(name="salesman_id", in="query", required=false, description="Filter ID Salesman", @OA\Schema(type="string")),
     *     @OA\Parameter(name="kind", in="query", required=false, description="Jenis: 0=Penjualan, 1=Retur", @OA\Schema(type="integer", enum={0,1})),
     *     @OA\Parameter(name="payment_type", in="query", required=false, description="Tipe Pembayaran (dari paymenttype.id)", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="per_page", in="query", required=false, description="Jumlah data per halaman (default: 15)", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Sukses"),
     *     @OA\Response(response=422, description="Validasi gagal"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function salesHistory(Request $request)
    {
        // --- 1. Validasi Parameter ---
        $request->validate([
            'date_from' => 'nullable|date_format:Y-m-d',
            'date_to' => 'nullable|date_format:Y-m-d|after_or_equal:date_from',
            'customer_id' => 'nullable|string',
            'salesman_id' => 'nullable|string',
            'kind' => 'nullable|in:0,1',
            'payment_type' => 'nullable|integer',
            'per_page' => 'nullable|integer|min:1|max:200',
        ]);

        // --- 2. Default Range: hari ini jika tidak diisi ---
        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : Carbon::today()->startOfDay();

        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : Carbon::today()->endOfDay();

        // --- 3. Build Query Header Faktur ---
        // Catatan: tabel `sales` tidak memiliki kolom totalamount / salesno.
        // Total dihitung dari SUM(salesdetail.netamount) via subquery.
        $query = DB::table('sales')
            ->join('customer', 'sales.customerid', '=', 'customer.id')
            ->join('salesman', 'sales.salesmanid', '=', 'salesman.id')
            ->select(
                'sales.salesid',
                'sales.salesidref',   // referensi dokumen asal (misal untuk retur)
                'sales.salesdate',
                'sales.salestime',
                'sales.kind',
                'sales.salestype',
                'customer.id as customer_id',
                'customer.name as customer_name',
                'salesman.id as salesman_id',
                'salesman.name as salesman_name',
                // Tipe pembayaran dari salespayments (MIN untuk ambil satu nilai representatif)
                DB::raw('(
                    SELECT MIN(sp.paymenttype)
                    FROM salespayments sp
                    WHERE sp.salesidref = sales.salesid
                ) as payment_type'),
                // Total nilai faktur = sum dari netamount di salesdetail
                DB::raw('(
                    SELECT COALESCE(SUM(sd.netamount), 0)
                    FROM salesdetail sd
                    WHERE sd.salesid = sales.salesid
                ) as net_amount')
            )
            ->whereBetween('sales.salesdate', [$dateFrom, $dateTo])
            ->orderBy('sales.salesdate', 'desc')
            ->orderBy('sales.salesid', 'desc');

        // --- 4. Filter Opsional ---

        if ($request->filled('kind')) {
            $query->where('sales.kind', (int) $request->kind);
        }

        if ($request->filled('customer_id')) {
            $query->where('sales.customerid', $request->customer_id);
        }

        if ($request->filled('salesman_id')) {
            $query->where('sales.salesmanid', $request->salesman_id);
        }

        // Filter tipe pembayaran via whereExists (hindari duplikasi baris)
        if ($request->filled('payment_type')) {
            $paymentType = (int) $request->payment_type;
            $query->whereExists(function ($sub) use ($paymentType) {
                $sub->select(DB::raw(1))
                    ->from('salespayments')
                    ->whereColumn('salespayments.salesidref', 'sales.salesid')
                    ->where('salespayments.paymenttype', $paymentType);
            });
        }

        // --- 5. Paginasi ---
        $perPage = $request->input('per_page', 15);
        $paginated = $query->paginate($perPage);

        // --- 6. Format Output ---
        $kindLabel = [0 => 'Penjualan', 1 => 'Retur'];
        $typeLabel = [0 => 'Cash', 1 => 'Cash on Delivery', 2 => 'Kredit'];

        $items = collect($paginated->items())->map(function ($row) use ($kindLabel, $typeLabel) {
            return [
                'salesid' => $row->salesid,
                'salesidref' => $row->salesidref ?: null,
                'salesdate' => $row->salesdate,
                'salestime' => $row->salestime,
                'kind' => $row->kind,
                'kind_label' => $kindLabel[$row->kind] ?? '-',
                'salestype' => $row->salestype,
                'salestype_label' => $typeLabel[$row->salestype] ?? '-',
                'payment_type' => $row->payment_type,
                'customer' => [
                    'id' => $row->customer_id,
                    'name' => $row->customer_name,
                ],
                'salesman' => [
                    'id' => $row->salesman_id,
                    'name' => $row->salesman_name,
                ],
                'net_amount' => (float) $row->net_amount,
            ];
        });

        return response()->json([
            'status' => 'success',
            'filter_applied' => [
                'date_from' => $dateFrom->toDateString(),
                'date_to' => $dateTo->toDateString(),
                'customer_id' => $request->customer_id,
                'salesman_id' => $request->salesman_id,
                'kind' => $request->kind !== null ? (int) $request->kind : null,
                'payment_type' => $request->payment_type !== null ? (int) $request->payment_type : null,
            ],
            'data' => $items,
            'pagination' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/sales/{salesid}/detail",
     *     tags={"Sales"},
     *     summary="Detail Item per Faktur",
     *     description="Mengambil semua baris item (salesdetail) dari satu faktur berdasarkan salesid.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
     *     @OA\Parameter(name="salesid", in="path", required=true, description="ID Faktur (sales.salesid)", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Sukses"),
     *     @OA\Response(response=404, description="Faktur tidak ditemukan"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function salesDetail(string $salesid)
    {
        // --- 1. Ambil Header Faktur ---
        $header = DB::table('sales')
            ->join('customer', 'sales.customerid', '=', 'customer.id')
            ->join('salesman', 'sales.salesmanid', '=', 'salesman.id')
            ->select(
                'sales.salesid',
                'sales.salesidref',
                'sales.salesdate',
                'sales.salestime',
                'sales.kind',
                'sales.salestype',
                'sales.memo',
                'customer.id as customer_id',
                'customer.name as customer_name',
                'salesman.id as salesman_id',
                'salesman.name as salesman_name'
            )
            ->where('sales.salesid', $salesid)
            ->first();

        if (!$header) {
            return response()->json([
                'status' => 'error',
                'message' => 'Faktur tidak ditemukan.',
            ], 404);
        }

        // --- 2. Ambil Detail Item ---
        $details = DB::table('salesdetail')
            ->join('product', 'salesdetail.productid', '=', 'product.id')
            ->select(
                'salesdetail.transid',
                'salesdetail.productid',
                'product.name as product_name',
                'salesdetail.unit',
                'salesdetail.salesqty',
                'salesdetail.returnqty',
                'salesdetail.price',
                'salesdetail.grossamount',
                'salesdetail.valuedisc',
                'salesdetail.percentdisc',
                'salesdetail.netamount',
                'salesdetail.cogs',
                'salesdetail.salestax',
                'salesdetail.memo'
            )
            ->where('salesdetail.salesid', $salesid)
            ->orderBy('salesdetail.transid', 'asc')
            ->get();

        // --- 3. Hitung Total Tagihan ---
        $grandTotal = $details->sum('netamount');

        // --- 4. Ambil Info Pembayaran ---
        $payments = DB::table('salespayments')
            ->join('paymenttype', 'salespayments.paymenttype', '=', 'paymenttype.id')
            ->select(
                'salespayments.transid',
                'salespayments.transdate',
                'salespayments.debit',
                'salespayments.paymenttype as payment_type_id',
                'paymenttype.name as payment_type_name',
                'salespayments.refpayment'
            )
            ->where('salespayments.salesidref', $salesid)
            ->get();

        $kindLabel = [0 => 'Penjualan', 1 => 'Retur'];
        $typeLabel = [0 => 'Cash', 1 => 'Cash on Delivery', 2 => 'Kredit'];

        return response()->json([
            'status' => 'success',
            'data' => [
                'header' => [
                    'salesid' => $header->salesid,
                    'salesidref' => $header->salesidref ?: null,
                    'salesdate' => $header->salesdate,
                    'salestime' => $header->salestime,
                    'kind' => $header->kind,
                    'kind_label' => $kindLabel[$header->kind] ?? '-',
                    'salestype' => $header->salestype,
                    'salestype_label' => $typeLabel[$header->salestype] ?? '-',
                    'memo' => $header->memo,
                    'customer' => [
                        'id' => $header->customer_id,
                        'name' => $header->customer_name,
                    ],
                    'salesman' => [
                        'id' => $header->salesman_id,
                        'name' => $header->salesman_name,
                    ],
                    'grand_total' => (float) $grandTotal,
                ],
                'items' => $details->map(function ($item) {
                    return [
                        'transid' => $item->transid,
                        'product_id' => $item->productid,
                        'product_name' => $item->product_name,
                        'unit' => $item->unit,
                        'qty' => (float) $item->salesqty,
                        'return_qty' => (float) $item->returnqty,
                        'price' => (float) $item->price,
                        'gross_amount' => (float) $item->grossamount,
                        'disc_value' => (float) $item->valuedisc,
                        'disc_percent' => $item->percentdisc,
                        'net_amount' => (float) $item->netamount,
                        'cogs' => (float) $item->cogs,
                        'tax' => (float) $item->salestax,
                        'memo' => $item->memo,
                    ];
                }),
                'payments' => $payments->map(function ($p) {
                    return [
                        'transid' => $p->transid,
                        'transdate' => $p->transdate,
                        'amount' => (float) $p->debit,
                        'payment_type_id' => $p->payment_type_id,
                        'payment_type_name' => $p->payment_type_name,
                        'ref' => $p->refpayment,
                    ];
                }),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/sales/orders",
     *     tags={"Sales"},
     *     summary="Daftar Sales Order",
     *     description="Mengambil daftar Sales Order dengan filter opsional berdasarkan customer dan kind.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
     *     @OA\Parameter(name="date_from", in="query", required=false, description="Tanggal mulai (YYYY-MM-DD)", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="date_to", in="query", required=false, description="Tanggal akhir (YYYY-MM-DD)", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="customer_id", in="query", required=false, description="Filter berdasarkan ID Customer", @OA\Schema(type="string")),
     *     @OA\Parameter(name="kind", in="query", required=false, description="Jenis order: 0=Standard, 1=Retur", @OA\Schema(type="integer", enum={0,1})),
     *     @OA\Parameter(name="per_page", in="query", required=false, description="Jumlah data per halaman (default: 15)", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Sukses"),
     *     @OA\Response(response=422, description="Validasi gagal"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function getSalesOrders(Request $request)
    {
        // --- 1. Validasi Parameter ---
        $request->validate([
            'date_from' => 'nullable|date_format:Y-m-d',
            'date_to' => 'nullable|date_format:Y-m-d|after_or_equal:date_from',
            'customer_id' => 'nullable|string',
            'kind' => 'nullable|in:0,1',
            'per_page' => 'nullable|integer|min:1|max:200',
        ]);

        // --- 2. Default Range: hari ini jika tidak diisi ---
        $dateFrom = $request->filled('date_from')
            ? Carbon::parse($request->date_from)->startOfDay()
            : Carbon::today()->startOfDay();

        $dateTo = $request->filled('date_to')
            ? Carbon::parse($request->date_to)->endOfDay()
            : Carbon::today()->endOfDay();

        // --- 3. Build Query ---
        $query = DB::table('salesorder')
            ->join('customer', 'salesorder.customerid', '=', 'customer.id')
            ->join('salesman', 'salesorder.salesmanid', '=', 'salesman.id')
            ->select(
                'salesorder.salesid',
                'salesorder.salesidref',
                'salesorder.salesdate',
                'salesorder.salestime',
                'salesorder.kind',
                'salesorder.salestype',
                'salesorder.accepted',
                'salesorder.dateaccepted',
                'salesorder.memo',
                'customer.id as customer_id',
                'customer.name as customer_name',
                'salesman.id as salesman_id',
                'salesman.name as salesman_name',
                // Total nilai SO dari salesorderdetail
                DB::raw('(
                    SELECT COALESCE(SUM(sod.netamount), 0)
                    FROM salesorderdetail sod
                    WHERE sod.salesid = salesorder.salesid
                ) as net_amount')
            )
            ->whereBetween('salesorder.salesdate', [$dateFrom, $dateTo])
            ->orderBy('salesorder.salesdate', 'desc')
            ->orderBy('salesorder.salesid', 'desc');

        // --- 4. Filter Opsional ---
        if ($request->filled('customer_id')) {
            $query->where('salesorder.customerid', $request->customer_id);
        }

        if ($request->filled('kind')) {
            $query->where('salesorder.kind', (int) $request->kind);
        }

        // --- 5. Paginasi ---
        $perPage = $request->input('per_page', 15);
        $paginated = $query->paginate($perPage);

        // --- 6. Format Output ---
        // kind label 0 sudah digunakan 1 masih dalam order bagaimana bahasanya
        $kindLabel = [0 => 'Order', 1 => 'Sales'];
        $typeLabel = [0 => 'Cash', 1 => 'Cash on Delivery', 2 => 'Kredit'];

        $items = collect($paginated->items())->map(function ($row) use ($kindLabel, $typeLabel) {
            return [
                'salesid' => $row->salesid,
                'salesidref' => $row->salesidref ?: null,
                'salesdate' => $row->salesdate,
                'salestime' => $row->salestime,
                'kind' => $row->kind,
                'kind_label' => $kindLabel[$row->kind] ?? '-',
                'salestype' => $row->salestype,
                'salestype_label' => $typeLabel[$row->salestype] ?? '-',
                'accepted' => (bool) $row->accepted,
                'dateaccepted' => $row->dateaccepted,
                'memo' => $row->memo,
                'customer' => [
                    'id' => $row->customer_id,
                    'name' => $row->customer_name,
                ],
                'salesman' => [
                    'id' => $row->salesman_id,
                    'name' => $row->salesman_name,
                ],
                'net_amount' => (float) $row->net_amount,
            ];
        });

        return response()->json([
            'status' => 'success',
            'filter_applied' => [
                'date_from' => $dateFrom->toDateString(),
                'date_to' => $dateTo->toDateString(),
                'customer_id' => $request->customer_id,
                'kind' => $request->kind !== null ? (int) $request->kind : null,
            ],
            'data' => $items,
            'pagination' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/sales/orders/{salesid}/detail",
     *     tags={"Sales"},
     *     summary="Detail Item per Sales Order",
     *     description="Mengambil semua baris item dari satu Sales Order berdasarkan salesid.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
     *     @OA\Parameter(name="salesid", in="path", required=true, description="ID Sales Order", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Sukses"),
     *     @OA\Response(response=404, description="Sales Order tidak ditemukan")
     * )
     */
    public function salesOrderDetail(string $salesid)
    {
        // --- 1. Ambil Header Sales Order ---
        $header = DB::table('salesorder')
            ->join('customer', 'salesorder.customerid', '=', 'customer.id')
            ->join('salesman', 'salesorder.salesmanid', '=', 'salesman.id')
            ->select(
                'salesorder.salesid',
                'salesorder.salesidref',
                'salesorder.salesdate',
                'salesorder.salestime',
                'salesorder.kind',
                'salesorder.salestype',
                'salesorder.accepted',
                'salesorder.dateaccepted',
                'salesorder.memo',
                'customer.id as customer_id',
                'customer.name as customer_name',
                'salesman.id as salesman_id',
                'salesman.name as salesman_name'
            )
            ->where('salesorder.salesid', $salesid)
            ->first();

        if (!$header) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sales Order tidak ditemukan.',
            ], 404);
        }

        // --- 2. Ambil Detail Item ---
        $details = DB::table('salesorderdetail')
            ->join('product', 'salesorderdetail.productid', '=', 'product.id')
            ->leftJoin('departement', 'salesorderdetail.departement', '=', 'departement.id')
            ->leftJoin('division', 'departement.division', '=', 'division.id')
            ->select(
                'salesorderdetail.transid',
                'salesorderdetail.productid',
                'product.name as product_name',
                'salesorderdetail.unit',
                'salesorderdetail.salesqty',
                'salesorderdetail.price',
                'salesorderdetail.grossamount',
                'salesorderdetail.valuedisc',
                'salesorderdetail.percentdisc',
                'salesorderdetail.netamount',
                'salesorderdetail.cogs',
                'salesorderdetail.salestax',
                'salesorderdetail.memo',
                'salesorderdetail.departement as departement_id',
                'departement.name as departement_name',
                'division.id as division_id',
                'division.description as division_name'
            )
            ->where('salesorderdetail.salesid', $salesid)
            ->orderBy('salesorderdetail.transid', 'asc')
            ->get();

        // --- 3. Hitung Total ---
        $grandTotal = $details->sum('netamount');

        $kindLabel = [0 => 'Order', 1 => 'Sales'];
        $typeLabel = [0 => 'Cash', 1 => 'Cash on Delivery', 2 => 'Kredit'];

        return response()->json([
            'status' => 'success',
            'data' => [
                'header' => [
                    'salesid' => $header->salesid,
                    'salesidref' => $header->salesidref ?: null,
                    'salesdate' => $header->salesdate,
                    'salestime' => $header->salestime,
                    'kind' => $header->kind,
                    'kind_label' => $kindLabel[$header->kind] ?? '-',
                    'salestype' => $header->salestype,
                    'salestype_label' => $typeLabel[$header->salestype] ?? '-',
                    'accepted' => (bool) $header->accepted,
                    'dateaccepted' => $header->dateaccepted,
                    'memo' => $header->memo,
                    'customer' => [
                        'id' => $header->customer_id,
                        'name' => $header->customer_name,
                    ],
                    'salesman' => [
                        'id' => $header->salesman_id,
                        'name' => $header->salesman_name,
                    ],
                    'grand_total' => (float) $grandTotal,
                ],
                'items' => $details->map(function ($item) {
                    return [
                        'transid' => $item->transid,
                        'product_id' => $item->productid,
                        'product_name' => $item->product_name,
                        'unit' => $item->unit,
                        'qty' => (float) $item->salesqty,
                        'price' => (float) $item->price,
                        'gross_amount' => (float) $item->grossamount,
                        'disc_value' => (float) $item->valuedisc,
                        'disc_percent' => $item->percentdisc,
                        'net_amount' => (float) $item->netamount,
                        'cogs' => (float) $item->cogs,
                        'tax' => (float) $item->salestax,
                        'memo' => $item->memo,
                        'departement_id' => $item->departement_id,
                        'departement_name' => $item->departement_name,
                        'division_id' => $item->division_id,
                        'division_name' => $item->division_name,
                    ];
                }),
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/sales/order",
     *     tags={"Sales"},
     *     summary="Membuat Sales Order Baru sekaligus Pemotongan Stok",
     *     description="Simpan transaksi SO baru dan langsung memotong tabel stok (Inventory).",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"salesdate", "salestype", "customerid", "division", "usercreate", "items"},
     *             @OA\Property(property="salesdate", type="string", format="date", example="2023-10-27"),
     *             @OA\Property(property="salestype", type="integer", example=0, description="0=Penjualan, 1=Retur"),
     *             @OA\Property(property="customerid", type="string", example="CUST001"),
     *             @OA\Property(property="salesmanid", type="string", example="SALES001", description="Opsional"),
     *             @OA\Property(property="division", type="string", example="01"),
     *             @OA\Property(property="usercreate", type="string", example="admin"),
     *             @OA\Property(property="items", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="productid", type="string", example="PROD001"),
     *                     @OA\Property(property="unit", type="string", example="PCS"),
     *                     @OA\Property(property="salesqty", type="number", format="float", example=2),
     *                     @OA\Property(property="price", type="number", format="float", example=100000),
     *                     @OA\Property(property="disc_percent", type="number", format="float", example=10),
     *                     @OA\Property(property="tax", type="number", format="float", example=11000),
     *                     @OA\Property(property="memo", type="string", example="Order dari Budi")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Berhasil"),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=500, description="Server Error")
     * )
     */
    public function storeSalesOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salesdate' => 'required|date',
            'salestype' => 'required|integer',
            'customerid' => 'required|string',
            'division' => 'required|string',
            'usercreate' => 'required|string',
            'details' => 'required|array|min:1',
            'details.*.productid' => 'required|string',
            'details.*.salesqty' => 'required|numeric',
            'details.*.unit' => 'required|string',
            'details.*.price' => 'required|numeric',
            'details.*.departement' => 'required|string',
        ]);

        Log::info("Request Sales Order: " . json_encode($request->all()));

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak lengkap atau tidak valid.',
                'errors' => $validator->errors()
            ], 400);
        }


        DB::beginTransaction();

        try {
            // A. Increment Auto ID untuk Sales Order ATAU Inventory
            $div = DB::table('division')->where('id', $request->division)->lockForUpdate()->first();

            if (!$div) {
                throw new \Exception("Divisi tidak ditemukan!");
            }

            $salesmanid = DB::table('customer')->where('id', $request->customerid)->pluck("defsalesmanid")->first() ?? "001";

            // 1. Format ID Sales Order
            $rawFormatSO = $div->frmsalesorderid;
            $prefixSO = str_replace('%0:6.6d', '', $rawFormatSO);
            $nextNumberSO = $div->salesorderidno + 1;
            $soId = $prefixSO . str_pad($nextNumberSO, 6, "0", STR_PAD_LEFT);

            // 2. Format ID Inventory (Sesuai instruksi khusus)
            // $rawFormatInv = $div->frminventoryid;
            // $prefixInv = str_replace('%0:6.6d', '', $rawFormatInv);
            // $nextNumberInv = $div->inventoryidno + 1;
            // $invId = $prefixInv . str_pad($nextNumberInv, 6, "0", STR_PAD_LEFT);

            // Update nomor urut di tabel divisi
            DB::table('division')
                ->where('id', $request->division)
                ->update([
                    'salesorderidno' => $nextNumberSO,
                ]);

            $currentTime = date('H:i:s');
            // $currentDateTime = $request->salesdate . ' ' . $currentTime;

            // B. INSERT KE TABEL HEADER: salesorder
            DB::table('salesorder')->insert([
                'salesid' => $soId,
                'salesidref' => $soId,
                'salesdate' => $request->salesdate,
                'salestime' => $currentTime,
                'salestype' => $request->salestype,
                'kind' => 0, // 0 = standard sale order
                'earlydiscdays' => 0,
                'earlydiscvalue' => 0,
                'duedays' => 0,
                'customerid' => $request->customerid,
                'currtrans' => 'IDR',
                'ratedefault' => 9000,
                'rateused' => 9000,
                'salesmanid' => $salesmanid,
                'salespercentdisc' => 0,
                'salesvaluedisc' => 0,
                'memo' => $request->memo ?? '-',
                'memoedit' => '-',
                'division' => $request->division,
                'printed' => 0,
                'shipment' => 0,
                'accepted' => 0,
                'dateaccepted' => null,
                'usercreate' => $request->usercreate,
                'useredit' => $request->usercreate,
                'paidinfull' => 0,
                'paidinfulldate' => null,
                'paidinfullref' => '',
                'taxprint' => 0,
                'taxprintid' => '',
                'orderid' => null,
                'billto' => $request->customerid,
                'shipto' => null
            ]);

            // C. Ambil Harga Pokok (cogs) dari product
            $productsData = DB::table('product')
                ->whereIn('id', collect($request->details)->pluck('productid'))
                ->get()
                ->keyBy('id');

            // D. LOOPING INSERT DETAIL & INVENTORY PENGURANGAN (Out)
            $dateRef = date('Ymd', strtotime($request->salesdate)) . mt_rand(10000000, 99999999);

            foreach ($request->details as $detail) {
                $qty = (float) $detail['salesqty'];
                $price = (float) $detail['price'];
                $gross = $qty * $price;

                $productDb = $productsData[$detail['productid']] ?? null;
                $cogs = $productDb ? (float) $productDb->costprice : 0;

                // 1. Insert Sales Order Detail
                DB::table('salesorderdetail')->insert([
                    'transdate' => $request->salesdate,
                    'salesid' => $soId,
                    'salesidref' => $soId,
                    'productid' => $detail['productid'],
                    'snproduct' => '',
                    'salesqty' => $qty,
                    'unit' => $detail['unit'],
                    'price' => $price,
                    'grossamount' => $gross,
                    'taxid' => 0,
                    'salestax' => 0,
                    'percentdisc' => '0',
                    'valuedisc' => 0,
                    'netamount' => $gross,
                    'cogs' => $cogs,
                    'memo' => '-',
                    'departement' => $detail['departement'],
                    'servicedoerid' => "001",
                    'usercreate' => $request->usercreate,
                    'useredit' => $request->usercreate,
                ]);

                // // 2. Insert tabel Inventory untuk memotong stok barang (- qty)
                // DB::table('inventory')->insert([
                //     'transid' => $invId,
                //     'transdate' => $currentDateTime,
                //     'departement' => "001101",
                //     'division' => $request->division,
                //     'supplier' => '001001',
                //     'productid' => $detail['productid'],
                //     'snproduct' => '',
                //     'invin' => 0,
                //     'invout' => $qty,
                //     'invvalue' => $cogs,
                //     'reference' => $soId,
                //     'datereference' => $dateRef,
                //     'transtype' => 3,
                //     'memo' => 'Stock Out for SO: ' . $soId,
                //     'usercreate' => $request->usercreate,
                //     'useredit' => '',
                //     'isempty' => 0
                // ]);
            }

            // E. COMMIT SELURUH TRANSAKSI
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order Penjualan berhasil disimpan dan stok terpotong.',
                'salesid' => $soId,
                // 'inventory_transid' => $invId
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/sales/store",
     *     tags={"Sales"},
     *     summary="Membuat Transaksi Penjualan (Faktur)",
     *     description="Simpan faktur penjualan (Tunai/Tempo), potong stok, buat jurnal akuntansi, dan catat pembayaran jika tunai.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"salesdate", "salestype", "customerid", "division", "usercreate", "details"},
     *             @OA\Property(property="salesdate", type="string", format="date", example="2026-04-14"),
     *             @OA\Property(property="salestype", type="integer", example=0, description="0=Tunai, 2=Tempo"),
     *             @OA\Property(property="customerid", type="string", example="001001"),
     *             @OA\Property(property="salesmanid", type="string", example="001", description="Opsional"),
     *             @OA\Property(property="division", type="string", example="0011"),
     *             @OA\Property(property="usercreate", type="string", example="security"),
     *             @OA\Property(property="memo", type="string", example="Penjualan khusus"),
     *             @OA\Property(property="salesorder_id_ref", type="string", example="I/SO-006519", description="Opsional referensi SO"),
     *             @OA\Property(property="details", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="productid", type="string", example="STP"),
     *                     @OA\Property(property="unit", type="string", example="PCS"),
     *                     @OA\Property(property="salesqty", type="number", format="float", example=2),
     *                     @OA\Property(property="price", type="number", format="float", example=15000),
     *                     @OA\Property(property="departement", type="string", example="001101"),
     *                     @OA\Property(property="supplier", type="string", example="001001")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Berhasil"),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=500, description="Server Error")
     * )
     */
    public function storeSale(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salesdate' => 'required|date',
            'salestype' => 'required|in:0,2',
            'customerid' => 'required|string',
            'division' => 'required|string',
            'usercreate' => 'required|string',
            'salesmanid' => 'nullable|string',
            'salesorder_id_ref' => 'nullable|string',
            'details' => 'required|array|min:1',
            'details.*.productid' => 'required|string',
            'details.*.salesqty' => 'required|numeric',
            'details.*.unit' => 'required|string',
            'details.*.price' => 'required|numeric',
            'details.*.departement' => 'required|string',
            'details.*.supplier' => 'nullable|string',
        ]);

        Log::info("Request Store Sale: " . json_encode($request->all()));

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak lengkap atau tidak valid.',
                'errors' => $validator->errors()
            ], 400);
        }

        DB::beginTransaction();


        try {
            $div = DB::table('division')->where('id', "0011")->lockForUpdate()->first();

            if (!$div) {
                throw new \Exception("Divisi tidak ditemukan!");
            }

            // Cukup 1 baris ini saja, sangat rapi!

            $salesmanid = DB::table('customer')->where('id', $request->customerid)->value('defsalesmanid') ?: "001";

            // 1. Format ID Sales
            $rawFormatSL = $div->frmsalesid ?? "I/SL-%0:6.6d";
            $prefixSL = str_replace('%0:6.6d', '', $rawFormatSL);
            $nextNumberSL = ($div->salesidno ?? 0) + 1;
            $salesId = $prefixSL . str_pad($nextNumberSL, 6, "0", STR_PAD_LEFT);

            while (DB::table('sales')->where('salesid', $salesId)->exists()) {
                $nextNumberSL++;
                $salesId = $prefixSL . str_pad($nextNumberSL, 6, "0", STR_PAD_LEFT);
            }

            // 2. Format ID Inventory
            $rawFormatInv = $div->frminventoryid ?? "I/IM-%0:6.6d";
            $prefixInv = str_replace('%0:6.6d', '', $rawFormatInv);
            $nextNumberInv = ($div->inventoryidno ?? 0) + 1;
            $invId = $prefixInv . str_pad($nextNumberInv, 6, "0", STR_PAD_LEFT);

            while (DB::table('inventory')->where('transid', $invId)->exists()) {
                $nextNumberInv++;
                $invId = $prefixInv . str_pad($nextNumberInv, 6, "0", STR_PAD_LEFT);
            }

            //departement log

            DB::table('division')
                ->where('id', $request->division)
                ->update([
                    'salesidno' => $nextNumberSL,
                    'inventoryidno' => $nextNumberInv,
                ]);



            $currentTime = date('H:i:s');
            $currentDateTime = $request->salesdate . ' ' . $currentTime;
            $isCash = ($request->salestype == 0);

            $memoType = $isCash ? "Penjualan: TUNAI" : "Penjualan: TEMPO";

            // 3. Header Sales
            DB::table('sales')->insert([
                'salesid' => $salesId,
                'salesidref' => $salesId,
                'salesdate' => $request->salesdate,
                'salestime' => $currentTime,
                'salestype' => $request->salestype,
                'kind' => 0,
                'earlydiscdays' => 0,
                'earlydiscvalue' => 0,
                'duedays' => 0,
                'customerid' => $request->customerid,
                'currtrans' => 'IDR',
                'ratedefault' => 9000,
                'rateused' => 9000,
                'salesmanid' => $salesmanid,
                'salespercentdisc' => 0,
                'salesvaluedisc' => 0,
                'memo' => $request->memo ?? '-',
                'memoedit' => '-',
                'division' => "0011",
                'printed' => 0,
                'shipment' => 0,
                'accepted' => -1,
                'dateaccepted' => $request->salesdate . ' 00:00:00',
                'usercreate' => $request->usercreate,
                'useredit' => $request->usercreate,
                'paidinfull' => $isCash ? 1 : 0,
                'paidinfulldate' => $isCash ? $request->salesdate : null,
                'paidinfullref' => $isCash ? $salesId : '',
                'taxprint' => 0,
                'taxprintid' => '010.000-10.00000001',
                'billto' => $request->customerid,
            ]);

            if ($request->filled('salesorder_id_ref')) {
                DB::table('salesorder')
                    ->where('salesid', $request->salesorder_id_ref)
                    ->update(['kind' => 1]); // 1 = Sudah diproses menjadi Penjualan
            }

            // 4. Ambil Harga Pokok (cogs) dari product
            $productsData = DB::table('product')
                ->whereIn('id', collect($request->details)->pluck('productid'))
                ->get()
                ->keyBy('id');

            // 5. Hitung & Insert Detail + Inventory
            $totalGross = 0;
            $totalNet = 0;
            $totalCogs = 0;
            $dateRef = date('Ymd', strtotime($request->salesdate)) . mt_rand(10000000, 99999999);

            foreach ($request->details as $detail) {
                $qty = (float) $detail['salesqty'];
                $price = (float) $detail['price'];

                $productDb = $productsData[$detail['productid']] ?? null;
                $unitCogs = $productDb ? (float) $productDb->costprice : 0;

                $gross = $qty * $price;
                $net = $gross;
                $totalCogsPerItem = $qty * $unitCogs;

                $totalGross += $gross;
                $totalNet += $net;
                $totalCogs += $totalCogsPerItem;


                DB::table('salesdetail')->insert([
                    'transdate' => $request->salesdate,
                    'salesid' => $salesId,
                    'salesidref' => $salesId,
                    'productid' => $detail['productid'],
                    'snproduct' => '',
                    'salesqty' => $qty,
                    'unit' => $detail['unit'],
                    'price' => $price,
                    'grossamount' => $gross,
                    'taxid' => 0,
                    'salestax' => 0,
                    'percentdisc' => '0',
                    'valuedisc' => 0,
                    'netamount' => $net,
                    'cogs' => $totalCogsPerItem,
                    'memo' => '-',
                    'departement' => $detail['departement'],
                    'servicedoerid' => "001",
                    'usercreate' => $request->usercreate,
                    'useredit' => $request->usercreate,
                ]);

                DB::table('inventory')->insert([
                    'transid' => $invId,
                    'transdate' => $currentDateTime,
                    'departement' => $detail['departement'],
                    'division' => "0011",
                    'supplier' => $detail['supplier'] ?? '001001',
                    'productid' => $detail['productid'],
                    'snproduct' => '',
                    'invin' => 0,
                    'invout' => $qty,
                    'invvalue' => $unitCogs,
                    'reference' => $salesId,
                    'datereference' => $dateRef,
                    'transtype' => 3,
                    'memo' => $memoType,
                    'usercreate' => $request->usercreate,
                    'useredit' => '',
                    'isempty' => 0
                ]);
            }

            $journals = [];

            if ($isCash) {
                $journals[] = ['accountid' => '101.001', 'debit' => $totalNet, 'credit' => 0];
            } else {
                $journals[] = ['accountid' => '104.001', 'debit' => $totalNet, 'credit' => 0];
            }
            $journals[] = ['accountid' => '401.001', 'debit' => 0, 'credit' => $totalNet];
            $journals[] = ['accountid' => '501.001', 'debit' => $totalCogs, 'credit' => 0];
            $journals[] = ['accountid' => '107.001', 'debit' => 0, 'credit' => $totalCogs];

            foreach ($journals as $j) {
                DB::table('journaltrans')->insert([
                    'jtid' => $salesId,
                    'jtdate' => $currentDateTime,
                    'memo' => $memoType,
                    'accountid' => $j['accountid'],
                    'debit' => $j['debit'],
                    'credit' => $j['credit'],
                    'division' => "0011",
                    'reftransaction' => 'Penjualan: ' . $salesId,
                    'usercreate' => $request->usercreate,
                    'useredit' => ''
                ]);
            }

            if ($isCash) {
                DB::table('salespayments')->insert([
                    'salesid' => $salesId,
                    'salesidref' => $salesId,
                    'transdate' => $currentDateTime,
                    'debit' => $totalNet,
                    'discountpercent' => 0,
                    'discountvalue' => 0,
                    'expensepercent' => 0,
                    'expensevalue' => 0,
                    'duedate' => $request->salesdate,
                    'accepted' => 1,
                    'paymenttype' => 1,
                    'refpayment' => '',
                    'currtrans' => 'IDR',
                    'ratedefault' => 9000,
                    'rateused' => 9000,
                    'usercreate' => $request->usercreate,
                    'useredit' => $request->usercreate,
                    'kind' => 0
                ]);
            }



            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi Penjualan berhasil disimpan.',
                'salesid' => $salesId,
                'inventory_transid' => $invId
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}