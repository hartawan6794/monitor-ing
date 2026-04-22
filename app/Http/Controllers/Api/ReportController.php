<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Log;

class ReportController extends Controller
{
    public function stockReport(Request $request)
    {
        // Parameter Filter dari Android
        $productId = $request->query('productid');
        $divisionId = $request->query('division');
        $departementId = $request->query('departement');
        $supplierId = $request->query('supplier');
        $groupId = $request->query('group');
        $brandId = $request->query('brand');

        // Query Utama dengan Join yang disesuaikan Schema
        $query = DB::table('product as p')
            ->leftJoin('inventory as i', 'p.id', '=', 'i.productid')
            ->leftJoin('productgroup as pg', 'p.productgroup', '=', 'pg.id')
            ->leftJoin('supplier as s', 'p.supplier', '=', 's.id')
            ->leftJoin('departement as d', 'i.departement', '=', 'd.id')
            ->leftJoin('division as div', 'i.division', '=', 'div.id')
            ->select(
                'p.id as productid',
                'p.name as productname',
                'p.aliasid as barcode',
                'p.defunit as unit',
                'p.minimum as min_stock',
                'pg.name as groupname',
                's.name as suppliername',
                'i.division as division_id',
                'div.description as division_name',
                'i.departement as departement_id',
                'd.name as dept_name',
                DB::raw('SUM(COALESCE(i.invin, 0) - COALESCE(i.invout, 0)) as total_stock'),
                DB::raw('CASE WHEN SUM(COALESCE(i.invin, 0) - COALESCE(i.invout, 0)) <= p.minimum THEN 1 ELSE 0 END as is_low_stock')
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
        if ($departementId)
            $query->where('i.departement', $departementId);


        $query->groupBy(
            'p.id', 'p.name', 'p.aliasid', 'p.defunit', 'p.minimum', 'pg.name', 's.name',
            'i.division', 'div.description', 'i.departement', 'd.name'
        )->havingRaw('SUM(COALESCE(i.invin, 0) - COALESCE(i.invout, 0)) <> 0');

        // Gunakan Pagination (default 50 per halaman)
        $paginatedData = $query->paginate(50);

        return response()->json([
            'status' => 'success',
            'data' => $paginatedData
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

        // --- Hitung Saldo Awal sebelum rentang tanggal ---
        $openingBalance = 0;
        if ($fromDate) {
            $openingBalance = (float) DB::table('inventory')
                ->where('productid', $productId)
                ->where('transdate', '<', $fromDate . ' 00:00:00')
                ->selectRaw('COALESCE(SUM(invin - invout), 0) as balance')
                ->value('balance');
        }

        // --- Tipe Transaksi menggunakan Global Helper ---
        $transTypeLabels = inventoryTranstypes();

        $query = DB::table('inventory as i')
            ->join('product as p', 'i.productid', '=', 'p.id')
            ->leftJoin('departement as d', 'i.departement', '=', 'd.id')
            ->leftJoin('division as dv', 'i.division', '=', 'dv.id')
            ->leftJoin('supplier as s', 'i.supplier', '=', 's.id')
            ->select(
                'i.transid',
                'i.transdate',
                'i.transtype',
                'i.invin',
                'i.invout',
                'i.reference',       // nomor dokumen sumber (I/SL, I/PR, MOV, dll)
                'i.memo as description',
                'i.usercreate',
                'd.name as dept_name',
                'dv.description as division_name',
                's.name as supplier_name',
                'p.defunit as unit',
            )
            ->where('i.productid', $productId);

        if ($fromDate && $toDate) {
            $query->whereBetween('i.transdate', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        $rows = $query->orderBy('i.transdate', 'asc')->get();

        // --- Tambahkan Running Balance dan Label ---
        $balance = $openingBalance;
        $result = $rows->map(function ($row) use (&$balance, $transTypeLabels) {
            $balance += ($row->invin - $row->invout);
            return [
                'transid'       => $row->transid,
                'transdate'     => $row->transdate,
                'transtype'     => $row->transtype,
                'transtype_label' => $transTypeLabels[$row->transtype] ?? 'Lainnya ('.$row->transtype.')',
                'reference'     => $row->reference,
                'invin'         => (float) $row->invin,
                'invout'        => (float) $row->invout,
                'balance'       => round($balance, 4),   // Saldo berjalan
                'unit'          => $row->unit,
                'description'   => $row->description,
                'dept_name'     => $row->dept_name,
                'division_name' => $row->division_name,
                'supplier_name' => $row->supplier_name,
                'usercreate'    => $row->usercreate,
            ];
        });

        return response()->json([
            'status' => 'success',
            'product_id' => $productId,
            'opening_balance' => $openingBalance,
            'closing_balance' => round($balance, 4),
            'data' => $result
        ]);
    }


    public function inOutReport(Request $request)
    {
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $deptId = $request->query('departement');
        $groupId = $request->query('group');
        $divisionId = $request->query('division');
        $search = $request->query('search');

        if (!$fromDate || !$toDate) {
            $fromDate = Carbon::today()->startOfMonth()->toDateString();
            $toDate = Carbon::today()->toDateString();
        }

        // Menggunakan Global Helper
        $transTypeLabels = inventoryTranstypes();

        $query = DB::table('inventory as i')
            ->join('product as p', 'i.productid', '=', 'p.id')
            ->leftJoin('departement as d', 'i.departement', '=', 'd.id')
            ->leftJoin('productgroup as pg', 'p.productgroup', '=', 'pg.id')
            ->select(
                'i.productid',
                'p.name as productname',
                'p.defunit as unit',
                'pg.name as groupname',
                'i.transtype',
                'd.name as dept_name',
                DB::raw('SUM(i.invin) as qty_in'),
                DB::raw('SUM(i.invout) as qty_out')
            )
            ->whereBetween('i.transdate', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);

        // Filter
        if ($deptId) $query->where('i.departement', $deptId);
        if ($divisionId) $query->where('i.division', $divisionId);
        if ($groupId) $query->where('p.productgroup', $groupId);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('p.id', 'like', "%$search%")->orWhere('p.name', 'like', "%$search%");
            });
        }

        $data = $query->groupBy('i.productid', 'p.name', 'p.defunit', 'pg.name', 'i.transtype', 'd.name')
            ->orderBy('p.name', 'asc')
            ->get();

        $result = $data->map(function ($row) use ($transTypeLabels) {
            return [
                'productid'   => $row->productid,
                'productname' => $row->productname,
                'unit'        => $row->unit,
                'group'       => $row->groupname,
                'dept_name'   => $row->dept_name ?? 'N/A',
                'type'        => $transTypeLabels[$row->transtype] ?? 'Lainnya ('.$row->transtype.')',
                'qty_in'      => (float) $row->qty_in,
                'qty_out'     => (float) $row->qty_out,
            ];
        });

        return response()->json([
            'status' => 'success',
            'period' => ['from' => $fromDate, 'to' => $toDate],
            'summary' => [
                'total_rows' => $result->count(),
                'grand_total_in' => $result->sum('qty_in'),
                'grand_total_out' => $result->sum('qty_out'),
            ],
            'data' => $result
        ]);
    }

    public function transferReport(Request $request)
    {
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $devisi = $request->query('division');

        // Query Utama dari tabel Sumber (Moving)
        $query = DB::table('inventorymoving as im')
            ->join('inventorymovingdetail as imd', 'im.id', '=', 'imd.transid')
            ->join('product as p', 'imd.productid', '=', 'p.id')
            ->leftJoin('departement as f', 'im.fromdepartement', '=', 'f.id')
            ->leftJoin('departement as t', 'im.todepartement', '=', 't.id')
            ->select(
                'im.id as transid',
                'im.transdate',
                'p.name as productname',
                'f.name as from_dept_name',
                't.name as to_dept_name',
                'imd.qty',
                'im.memo',
                'im.usercreate'
            );

        if ($fromDate && $toDate) {
            $query->whereBetween('im.transdate', [$fromDate, $toDate]);
        } else {
            $query->limit(100);
        }

        if ($devisi) {
            $query->where('im.division', $devisi);
        }

        $data = $query->orderBy('im.transdate', 'desc')
            ->orderBy('im.id', 'desc')
            ->get();

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
        $devId = $request->query('division');

        $query = DB::table('inventoryadjust as ia')
            ->join('inventoryadjustdetail as iad', 'ia.id', '=', 'iad.transid')
            ->join('product as p', 'iad.productid', '=', 'p.id')
            ->leftJoin('departement as d', 'iad.departement', '=', 'd.id')
            ->select(
                'ia.id as transid',
                'ia.transdate',
                'p.name as productname',
                'p.id as sku',
                'd.name as dept_name',
                'iad.recorded as stock_system',
                'iad.physical as stock_opname',
                DB::raw('iad.inqty as invin'),
                DB::raw('iad.outqty as invout'),
                'ia.memo',
                'ia.usercreate'
            );

        if ($fromDate && $toDate) {
            $query->whereBetween('ia.transdate', [$fromDate, $toDate]);
        }

        if ($deptId) {
            $query->where('iad.departement', $deptId);
        }

        if ($devId) {
            $query->where('iad.division', $devId);
        }

        $data = $query->orderBy('ia.transdate', 'desc')
            ->orderBy('ia.id', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}