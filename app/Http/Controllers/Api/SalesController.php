<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
            'date_from'    => 'nullable|date_format:Y-m-d',
            'date_to'      => 'nullable|date_format:Y-m-d|after_or_equal:date_from',
            'customer_id'  => 'nullable|string',
            'salesman_id'  => 'nullable|string',
            'kind'         => 'nullable|in:0,1',
            'payment_type' => 'nullable|integer',
            'per_page'     => 'nullable|integer|min:1|max:200',
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
        $perPage   = $request->input('per_page', 15);
        $paginated = $query->paginate($perPage);

        // --- 6. Format Output ---
        $kindLabel = [0 => 'Penjualan', 1 => 'Retur'];
        $typeLabel = [0 => 'Cash', 1 => 'Cash on Delivery', 2 => 'Kredit'];

        $items = collect($paginated->items())->map(function ($row) use ($kindLabel, $typeLabel) {
            return [
                'salesid'       => $row->salesid,
                'salesidref'    => $row->salesidref ?: null,
                'salesdate'     => $row->salesdate,
                'salestime'     => $row->salestime,
                'kind'          => $row->kind,
                'kind_label'    => $kindLabel[$row->kind] ?? '-',
                'salestype'     => $row->salestype,
                'salestype_label' => $typeLabel[$row->salestype] ?? '-',
                'payment_type'  => $row->payment_type,
                'customer'      => [
                    'id'   => $row->customer_id,
                    'name' => $row->customer_name,
                ],
                'salesman'      => [
                    'id'   => $row->salesman_id,
                    'name' => $row->salesman_name,
                ],
                'net_amount'    => (float) $row->net_amount,
            ];
        });

        return response()->json([
            'status'         => 'success',
            'filter_applied' => [
                'date_from'    => $dateFrom->toDateString(),
                'date_to'      => $dateTo->toDateString(),
                'customer_id'  => $request->customer_id,
                'salesman_id'  => $request->salesman_id,
                'kind'         => $request->kind !== null ? (int) $request->kind : null,
                'payment_type' => $request->payment_type !== null ? (int) $request->payment_type : null,
            ],
            'data'           => $items,
            'pagination'     => [
                'current_page' => $paginated->currentPage(),
                'per_page'     => $paginated->perPage(),
                'total'        => $paginated->total(),
                'last_page'    => $paginated->lastPage(),
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
                'status'  => 'error',
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
            'data'   => [
                'header' => [
                    'salesid'         => $header->salesid,
                    'salesidref'      => $header->salesidref ?: null,
                    'salesdate'       => $header->salesdate,
                    'salestime'       => $header->salestime,
                    'kind'            => $header->kind,
                    'kind_label'      => $kindLabel[$header->kind] ?? '-',
                    'salestype'       => $header->salestype,
                    'salestype_label' => $typeLabel[$header->salestype] ?? '-',
                    'memo'            => $header->memo,
                    'customer'        => [
                        'id'   => $header->customer_id,
                        'name' => $header->customer_name,
                    ],
                    'salesman'        => [
                        'id'   => $header->salesman_id,
                        'name' => $header->salesman_name,
                    ],
                    'grand_total'     => (float) $grandTotal,
                ],
                'items'    => $details->map(function ($item) {
                    return [
                        'transid'      => $item->transid,
                        'product_id'   => $item->productid,
                        'product_name' => $item->product_name,
                        'unit'         => $item->unit,
                        'qty'          => (float) $item->salesqty,
                        'return_qty'   => (float) $item->returnqty,
                        'price'        => (float) $item->price,
                        'gross_amount' => (float) $item->grossamount,
                        'disc_value'   => (float) $item->valuedisc,
                        'disc_percent' => $item->percentdisc,
                        'net_amount'   => (float) $item->netamount,
                        'cogs'         => (float) $item->cogs,
                        'tax'          => (float) $item->salestax,
                        'memo'         => $item->memo,
                    ];
                }),
                'payments' => $payments->map(function ($p) {
                    return [
                        'transid'           => $p->transid,
                        'transdate'         => $p->transdate,
                        'amount'            => (float) $p->debit,
                        'payment_type_id'   => $p->payment_type_id,
                        'payment_type_name' => $p->payment_type_name,
                        'ref'               => $p->refpayment,
                    ];
                }),
            ],
        ]);
    }
}