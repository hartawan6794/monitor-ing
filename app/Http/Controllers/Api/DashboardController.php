<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    /**
     * @OA\Get(
     *     path="/dashboard",
     *     tags={"Dashboard"},
     *     summary="Ringkasan penjualan",
     *     description="Mengambil total penjualan untuk 4 periode.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Response(
     *         response=200,description="Sukses",
     *         @OA\JsonContent(@OA\Property(property="status", type="string", example="success"))
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function getDashboardData()
    {
        $stats = DB::table('salespayments')
            ->selectRaw("
            SUM(CASE WHEN DATE(transdate) = CURRENT_DATE THEN debit ELSE 0 END) as today,
            SUM(CASE WHEN YEARWEEK(transdate, 1) = YEARWEEK(CURRENT_DATE, 1) THEN debit ELSE 0 END) as weekly,
            SUM(CASE WHEN MONTH(transdate) = MONTH(CURRENT_DATE) AND YEAR(transdate) = YEAR(CURRENT_DATE) THEN debit ELSE 0 END) as monthly,
            SUM(CASE WHEN YEAR(transdate) = YEAR(CURRENT_DATE) THEN debit ELSE 0 END) as yearly
        ")
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'today' => (float) ($stats->today ?? 0),
                'weekly' => (float) ($stats->weekly ?? 0),
                'monthly' => (float) ($stats->monthly ?? 0),
                'yearly' => (float) ($stats->yearly ?? 0),
            ]
        ]);
    }


    /**
     * @OA\Get(
     *     path="/dashboard/salesman",
     *     tags={"Dashboard"},
     *     summary="Penjualan harian per salesman",
     *     description="Mengambil total penjualan masing-masing salesman untuk hari ini.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Response(response=200, description="Sukses"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function getDailySalesBySalesman()
    {
        $salesBySalesman = DB::table('salespayments')
            ->join('sales', 'salespayments.salesidref', '=', 'sales.salesid')
            ->join('salesman', 'sales.salesmanid', '=', 'salesman.id')
            ->whereDate('salespayments.transdate', Carbon::today())
            ->select('salesman.name', DB::raw('SUM(salespayments.debit) as total_sales'))
            ->groupBy('salesman.id', 'salesman.name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $salesBySalesman
        ]);
    }

    /**
     * @OA\Get(
     *     path="/dashboard/salesman/yearly",
     *     tags={"Dashboard"},
     *     summary="Penjualan tahunan per salesman",
     *     description="Mengambil total penjualan masing-masing salesman sepanjang tahun berjalan.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Response(response=200, description="Sukses"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function getYearlySalesBySalesman()
    {
        $salesBySalesman = DB::table('salespayments')
            ->join('sales', 'salespayments.salesidref', '=', 'sales.salesid')
            ->join('salesman', 'sales.salesmanid', '=', 'salesman.id')
            ->whereYear('salespayments.transdate', Carbon::today()->year)
            ->select('salesman.id', 'salesman.name', DB::raw('SUM(salespayments.debit) as total_sales'))
            ->groupBy('salesman.id', 'salesman.name')
            ->orderBy('total_sales', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $salesBySalesman
        ]);
    }

    /**
     * @OA\Get(
     *     path="/dashboard/summary",
     *     tags={"Dashboard"},
     *     summary="Ringkasan penjualan (Omzet, Laba, Margin)",
     *     description="Menghitung omzet, laba kotor, dan margin untuk 4 periode: hari ini, minggu ini, bulan ini, dan tahun ini.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Response(response=200, description="Sukses"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function summary()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();
        $now = Carbon::now();

        // $startOfWeek = Carbon::now()->subDays(7)->startOfDay();
        // $startOfMonth = Carbon::now()->subDays(30)->startOfDay();
        // $startOfYear = Carbon::now()->subDays(365)->startOfDay();

        return response()->json([
            'status' => 'success',
            'data' => [
                'today' => $this->calculateMetrics($today, $now),
                'week' => $this->calculateMetrics($startOfWeek, $now),
                'month' => $this->calculateMetrics($startOfMonth, $now),
                'year' => $this->calculateMetrics($startOfYear, $now),
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/dashboard/owner-summary",
     *     tags={"Dashboard"},
     *     summary="Ringkasan Penjualan Owner (Omzet, Laba, Margin)",
     *     description="Menghitung omzet, laba kotor, dan margin untuk 6 periode: hari ini, kemarin, minggu ini, minggu lalu, bulan ini, dan bulan lalu.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Response(response=200, description="Sukses"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function ownerSummary()
    {
        $now = Carbon::now();
        $today = [$now->copy()->startOfDay(), $now];
        $yesterday = [$now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay()];

        $startOfWeek = $now->copy()->startOfWeek();
        $startOfLastWeek = $now->copy()->subWeek()->startOfWeek();
        $endOfLastWeek = $now->copy()->subWeek()->endOfWeek();

        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $resToday = $this->calculateMetrics($today[0], $today[1]);
        $resYesterday = $this->calculateMetrics($yesterday[0], $yesterday[1]);

        $resWeek = $this->calculateMetrics($startOfWeek, $now);
        $resLastWeek = $this->calculateMetrics($startOfLastWeek, $endOfLastWeek);

        $resMonth = $this->calculateMetrics($startOfMonth, $now);
        $resLastMonth = $this->calculateMetrics($startOfLastMonth, $endOfLastMonth);

        return response()->json([
            'status' => 'success',
            'data' => [
                'hari_ini' => array_merge($resToday, ['growth' => $this->calculateGrowth($resToday, $resYesterday)]),
                'kemarin' => $resYesterday,
                'minggu_ini' => array_merge($resWeek, ['growth' => $this->calculateGrowth($resWeek, $resLastWeek)]),
                'minggu_lalu' => $resLastWeek,
                'bulan_ini' => array_merge($resMonth, ['growth' => $this->calculateGrowth($resMonth, $resLastMonth)]),
                'bulan_lalu' => $resLastMonth,
            ]
        ]);
    }

    /**
     * Helper untuk menghitung Omzet, Laba, Margin per rentang waktu
     */
    private function calculateMetrics($startDate, $endDate)
    {
        // 1. Gross Sales Net Amount dari Sales Detail (kind = 0)
        $totalNetDetail = (float) DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->whereBetween('sales.salesdate', [$startDate, $endDate])
            ->where('sales.kind', 0)
            ->sum('salesdetail.netamount');

        // 2. Total Global Discount dari Sales Header (kind = 0)
        // Dihitung terpisah dari sales langsung agar tidak terduplikasi oleh item detail
        $totalDiscount = (float) DB::table('sales')
            ->whereBetween('salesdate', [$startDate, $endDate])
            ->where('kind', 0)
            ->sum('salesvaluedisc');

        // Omzet = Gross Sales - Global Discount
        $omzet = $totalNetDetail - $totalDiscount;

        // Total Invoices (kind = 0)
        $totalInvoices = DB::table('sales')
            ->whereBetween('salesdate', [$startDate, $endDate])
            ->where('kind', 0)
            ->count('salesid');

        // COGS (HPP) = Total Cost dari Sales Detail (kind = 0)
        $cogs = (float) DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->whereBetween('sales.salesdate', [$startDate, $endDate])
            ->where('sales.kind', 0)
            ->sum('salesdetail.cogs');

        $laba = $omzet - $cogs;
        // Menggunakan presisi 2 desimal sesuai ROUND(..., 2) di query SQL Anda
        $margin = $omzet > 0 ? round(($laba / $omzet) * 100, 2) : 0;
        $avgInvoice = $totalInvoices > 0 ? round($omzet / $totalInvoices, 0) : 0;

        return [
            'omzet' => $omzet,
            'laba' => $laba,
            'margin' => $margin,
            'total_invoice' => $totalInvoices,
            'avg_invoice' => $avgInvoice
        ];
    }


    public function topProducts(Request $request)
    {
        $dates = $this->getDateRangeFromRequest($request);
        $products = $this->getTopProductsData($dates);

        return response()->json(['status' => 'success', 'data' => $products]);
    }

    /**
     * Helper Chart/List Top Products
     */
    private function getTopProductsData($dates)
    {
        return DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->join('product', 'salesdetail.productid', '=', 'product.id')
            ->select('product.id', 'product.name', DB::raw('SUM(salesdetail.salesqty) as total_qty'), DB::raw('SUM(salesdetail.netamount) as total_net'))
            ->whereBetween('salesdetail.transdate', $dates)
            ->where('sales.kind', 0) // Hanya hitung penjualan normal, abaikan retur (kind=1)
            ->groupBy('product.id', 'product.name')
            ->orderByDesc('total_qty') // Gudang butuh qty terlaris, sales butuh net. Kita pakai total_qty.
            ->limit(5)
            ->get()
            ->map(function ($item, $index) {
                return [
                    'rank' => $index + 1,
                    'id' => $item->id,
                    'sku' => $item->id, // Alias untuk Gudang FE
                    'name' => $item->name, // Alias untuk Gudang FE
                    'title' => $item->name,
                    'qty' => $item->total_qty,
                    'subtitle' => number_format($item->total_qty, 0, ',', '.') . ' Terjual',
                    // Fields for Web Dashboard
                    'product_name' => $item->name,
                    'total_qty' => $item->total_qty,
                    'total_net' => $item->total_net
                ];
            });
    }

    /**
     * 3. GET TOP SALESMEN (Berdasarkan Total Omzet/Debit)
     * Menerima query param: ?period=today|month|year
     */
    public function topSalesmen(Request $request)
    {
        $dates = $this->getDateRangeFromRequest($request);

        $salesmen = DB::table('salespayments')
            ->join('sales', 'salespayments.salesidref', '=', 'sales.salesid')
            ->join('salesman', 'sales.salesmanid', '=', 'salesman.id')
            ->select('salesman.name', DB::raw('SUM(salespayments.debit) as omzet'), DB::raw('COUNT(DISTINCT sales.salesid) as total_invoice'))
            ->whereBetween('salespayments.transdate', $dates)
            ->groupBy('salesman.id', 'salesman.name')
            ->orderByDesc('omzet')
            ->limit(5)
            ->get()
            ->map(function ($item, $index) {
                return [
                    'rank' => $index + 1,
                    'title' => $item->name,
                    'subtitle' => 'Omzet: Rp ' . number_format($item->omzet, 0, ',', '.'),
                    // Fields for web dashboard
                    'salesman_name' => $item->name,
                    'total_sales' => $item->omzet,
                    'total_invoice' => $item->total_invoice
                ];
            });

        return response()->json(['status' => 'success', 'data' => $salesmen]);
    }

    public function topServiceDoers(Request $request)
    {
        $dates = $this->getDateRangeFromRequest($request);
        $limit = (int) $request->query('limit', 10);

        $doers = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->join('servicedoer', 'salesdetail.servicedoerid', '=', 'servicedoer.id')
            ->select(
                'servicedoer.id as doer_id',
                'servicedoer.name as doer_name',
                DB::raw('SUM(salesdetail.netto) as total_omset'),
                DB::raw('COUNT(DISTINCT salesdetail.salesid) as total_invoice'),
                DB::raw('SUM(salesdetail.qty) as total_qty')
            )
            ->whereBetween('sales.transdate', $dates)
            ->whereNotNull('salesdetail.servicedoerid')
            ->where('salesdetail.servicedoerid', '!=', '')
            ->groupBy('servicedoer.id', 'servicedoer.name')
            ->orderByDesc('total_omset')
            ->limit($limit)
            ->get()
            ->map(function ($item, $index) {
                return [
                    'rank' => $index + 1,
                    'doer_id' => $item->doer_id,
                    'doer_name' => $item->doer_name,
                    'total_omset' => (float) $item->total_omset,
                    'total_invoice' => (int) $item->total_invoice,
                    'total_qty' => (float) $item->total_qty,
                    // Format siap pakai untuk display di Android
                    'title' => $item->doer_name,
                    'subtitle' => 'Omset: Rp ' . number_format($item->total_omset, 0, ',', '.'),
                ];
            });

        return response()->json(['status' => 'success', 'data' => $doers]);
    }


    public function revenueChart()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $chartData = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->whereBetween('sales.salesdate', [$startDate, $endDate])
            ->where('sales.kind', 0)
            ->groupBy(DB::raw('DATE(sales.salesdate)'))
            ->orderBy(DB::raw('DATE(sales.salesdate)'), 'asc')
            ->select(DB::raw('DATE(sales.salesdate) as date'), DB::raw('SUM(salesdetail.netamount) as total'))
            ->get();

        return response()->json(['status' => 'success', 'data' => $chartData]);
    }

    /**
     * Helper untuk menterjemahkan filter string menjadi array tanggal [Start, End]
     */
    private function getDateRange($period)
    {
        $now = Carbon::now();
        switch ($period) {
            case 'week':
                return [Carbon::now()->startOfWeek(), $now];
            case 'month':
                return [Carbon::now()->startOfMonth(), $now];
            case 'year':
                return [Carbon::now()->startOfYear(), $now];
            case 'today':
            default:
                return [Carbon::today(), $now];
        }
    }

    /**
     * @OA\Get(
     *     path="/dashboard/salesman-view",
     *     tags={"Dashboard"},
     *     summary="Dashboard Salesman",
     *     description="Mengambil data total penjualan, return penjualan, penjualan tunai, penerimaan piutang, biaya operasional, kas di tangan dan penjualan & order 7 hari terakhir.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Response(response=200, description="Sukses"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function salesDashboard()
    {
        $today = Carbon::today();
        $startDate7 = Carbon::today()->subDays(6);

        // 1. Total Penjualan Hari Ini
        $total_penjualan = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->whereDate('sales.salesdate', $today)
            ->where('sales.kind', 0)
            ->sum('salesdetail.netamount');

        $return_penjualan = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->whereDate('sales.salesdate', $today)
            ->where('sales.kind', 1)
            ->sum('salesdetail.netamount');

        // 3. Penjualan Tunai Hari Ini (Join dengan salespayments paymenttype = 1)
        // Kita menggunakan whereExists agar tidak terjadi duplikasi penjumlahan jika ada multiple payment di satu faktur
        $penjualan_tunai = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->whereDate('sales.salesdate', $today)
            ->where('sales.kind', 0)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('salespayments')
                    ->whereColumn('salespayments.salesidref', 'sales.salesid')
                    ->where('salespayments.paymenttype', 1);
            })
            ->sum('salesdetail.netamount');

        // 4. Penerimaan Piutang Hari Ini (Tabel receivablepayment)
        $penerimaan_piutang = DB::table('receivablepayment')
            ->whereDate('transdate', $today)
            ->sum('amount');

        // 5. Biaya Operasional Hari Ini (Dari jurnal spesifik akun 603)
        $biaya_operasional = DB::table('journaltrans')
            ->join('journal', 'journaltrans.jtid', '=', 'journal.jtid')
            ->join('account', 'journaltrans.accountid', '=', 'account.id')
            ->whereDate('journal.jtdate', $today)
            ->where('account.accountgroup', '603')
            ->sum(DB::raw('journaltrans.debit - journaltrans.credit')); // Sesuai dengan pembukuan biaya (debit)

        // 6. Kas Kecil (Saldo Kas Kecil hari ini)
        // Saldo = akumulasi transaksi (debit - credit) dari awal sampai dengan hari ini
        $kas_di_tangan = DB::table('journaltrans')
            ->join('journal', 'journaltrans.jtid', '=', 'journal.jtid')
            ->whereDate('journal.jtdate', '<=', $today) // Gunakan akumulasi waktu untuk Saldo/Balance
            ->where('journaltrans.accountid', '101.002')
            ->where('journal.approved', 1)
            ->sum(DB::raw('journaltrans.debit - journaltrans.credit'));

        // Gunakan helper untuk mendapatkan data chart
        $chartData = $this->getSalesAndOrderChart($startDate7, $today);

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_penjualan' => (float) $total_penjualan,
                'return_penjualan' => (float) $return_penjualan,
                'penjualan_tunai' => (float) $penjualan_tunai,
                'penerimaan_piutang' => (float) $penerimaan_piutang,
                'biaya_operasional' => (float) $biaya_operasional,
                'kas_di_tangan' => (float) $kas_di_tangan,
                'chart_7_hari' => [
                    'penjualan' => $chartData['penjualan'],
                    'order' => $chartData['order']
                ]
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/dashboard/chart-monthly",
     *     tags={"Dashboard"},
     *     summary="Grafik Penjualan Bulanan (Bulanan dalam 1 tahun) untuk Dashboard Web",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Response(response=200, description="Sukses")
     * )
     */
    public function monthlyChart()
    {
        $currentYear = Carbon::now()->year;

        // Ambil penjualan bulanan (kind = 0)
        $sales = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->whereYear('sales.salesdate', $currentYear)
            ->where('sales.kind', 0)
            ->selectRaw('MONTH(sales.salesdate) as month, SUM(salesdetail.netamount) as total')
            ->groupByRaw('MONTH(sales.salesdate)')
            ->pluck('total', 'month')->toArray();

        // Ambil retur bulanan (kind = 1)
        $returns = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->whereYear('sales.salesdate', $currentYear)
            ->where('sales.kind', 1)
            ->selectRaw('MONTH(sales.salesdate) as month, SUM(salesdetail.netamount) as total')
            ->groupByRaw('MONTH(sales.salesdate)')
            ->pluck('total', 'month')->toArray();

        // Format ke dalam array 12 bulan
        $salesArray = [];
        $returnsArray = [];
        for ($i = 1; $i <= 12; $i++) {
            $salesArray[] = isset($sales[$i]) ? (float) $sales[$i] : 0;
            $returnsArray[] = isset($returns[$i]) ? (float) $returns[$i] : 0;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                'penjualan' => $salesArray,
                'retur' => $returnsArray
            ]
        ]);
    }
    /**
     * @OA\Get(
     *     path="/dashboard/salesman-monthly",
     *     tags={"Dashboard"},
     *     summary="Dashboard Sales - Omset & Jumlah Order Bulan Ini",
     *     description="Mengambil total omset dan jumlah order bulan berjalan berdasarkan salesmanid yang login.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(name="salesman_id", in="query", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Sukses"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function salesmanDashboard(Request $request)
    {
        $salesmanId = $request->query('salesman_id'); // username login yang dikirim FE

        // // Ambil default salesmanid dari usersconfig (rule 027002)
        $salesmanData = DB::table('salesman')
            ->where('id', $salesmanId)
            ->first();

        if (!$salesmanData) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data salesman tidak ditemukan.',
            ], 404);
        }

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 1. Total Omset Penjualan Bulan Ini (kind=0 = Penjualan)
        $omset_bulan_ini = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->whereBetween('sales.salesdate', [$startOfMonth, $endOfMonth])
            ->where('sales.kind', 0)
            ->where('sales.salesmanid', $salesmanId)
            ->sum('salesdetail.netamount');

        // 2. Total Nilai Retur Bulan Ini (kind=1 = Retur Penjualan)
        $retur_bulan_ini = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->whereBetween('sales.salesdate', [$startOfMonth, $endOfMonth])
            ->where('sales.kind', 1)
            ->where('sales.salesmanid', $salesmanId)
            ->sum('salesdetail.netamount');

        // 3. Order Penjualan Bulan Ini (salestype=0 = Cash, kind=0)
        $order_penjualan = DB::table('salesorderdetail')
            ->join('salesorder', 'salesorderdetail.salesid', '=', 'salesorder.salesid')
            ->whereBetween('salesorder.salesdate', [$startOfMonth, $endOfMonth])
            ->where('salesorder.salestype', 2) // 0 = Tunai/Cash
            ->where('salesorder.salesmanid', $salesmanId)
            ->sum('salesorderdetail.netamount');

        // 4. Jumlah Order Bulan Ini (dari salesorder, bukan sales)
        $jumlah_order_bulan_ini = DB::table('salesorder')
            ->whereBetween('salesdate', [$startOfMonth, $endOfMonth])
            ->where('salesmanid', $salesmanId)
            ->count('salesid');

        // Ambil data chart (H-6 sampai hari ini)
        $startDate7 = Carbon::today()->subDays(6);
        $today = Carbon::today();

        $chartPenjualan = $this->getSalesChart($startDate7, $today, 'sales.salesmanid', $salesmanId);
        $chartOrder = $this->getOrderChart($startDate7, $today, $salesmanId);

        return response()->json([
            'status' => 'success',
            'data' => [
                'period' => [
                    'month' => Carbon::now()->locale('id')->translatedFormat('F Y'),
                    'start' => $startOfMonth->format('d-m-Y'),
                    'end' => $endOfMonth->format('d-m-Y'),
                ],
                'salesman_id' => $salesmanId,
                'omset_bulan_ini' => (float) $omset_bulan_ini,
                'retur_bulan_ini' => (float) $retur_bulan_ini,
                'order_bulan_ini' => (float) $order_penjualan,
                'jumlah_order_bulan_ini' => (int) $jumlah_order_bulan_ini,
                'chart_7_hari' => [
                    'labels' => $chartPenjualan['labels'],
                    'penjualan' => $chartPenjualan['data'],
                    'order' => $chartOrder['data']
                ]
            ]
        ]);
    }

    /**
     * Helper Chart Penjualan (Bisa filter by salesmanid atau usercreate)
     */
    private function getSalesChart($startDate, $endDate, $filterField, $filterValue)
    {
        // 1. Ambil Gross Amount (Harga sebelum diskon global) dari Detail
        $grossSales = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->select('sales.salesdate as tanggal', DB::raw('SUM(salesdetail.netamount) as total'))
            ->where('sales.kind', 0)
            ->whereBetween('sales.salesdate', [$startDate, $endDate])
            ->when($filterValue, fn($q) => $q->where($filterField, $filterValue))
            ->groupBy('sales.salesdate')
            ->pluck('total', 'tanggal');

        // 2. Ambil Global Discount dari Header (Agar tidak double/triple count saat join dengan detail)
        $globalDiscounts = DB::table('sales')
            ->select('salesdate as tanggal', DB::raw('SUM(salesvaluedisc) as total_disc'))
            ->where('kind', 0)
            ->whereBetween('salesdate', [$startDate, $endDate])
            ->when($filterValue, fn($q) => $q->where($filterField, $filterValue))
            ->groupBy('salesdate')
            ->pluck('total_disc', 'tanggal');

        // 3. Susun slot tanggal
        $diffInDays = $startDate->diffInDays($endDate);
        $labels = [];
        $data = [];

        for ($i = $diffInDays; $i >= 0; $i--) {
            $carbonDate = Carbon::parse($endDate)->subDays($i);
            $date = $carbonDate->toDateString();

            // Paksa locale ke 'id' untuk bahasa Indonesia
            $labels[] = $carbonDate->locale('id')->translatedFormat('l');

            $gross = isset($grossSales[$date]) ? (float) $grossSales[$date] : 0.0;
            $disc = isset($globalDiscounts[$date]) ? (float) $globalDiscounts[$date] : 0.0;

            // Net Amount = Gross dari Detail - Diskon Global Faktur
            $data[] = max(0, $gross - $disc);
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Helper Chart Order (Spesifik Salesman)
     */
    private function getOrderChart($startDate, $endDate, $salesmanId)
    {
        $chart_order = DB::table('salesorderdetail')
            ->join('salesorder', 'salesorderdetail.salesid', '=', 'salesorder.salesid')
            ->select('salesorder.salesdate as tanggal', DB::raw('SUM(salesorderdetail.netamount) as total'))
            ->whereBetween('salesorder.salesdate', [$startDate, $endDate])
            ->when($salesmanId, fn($q) => $q->where('salesorder.salesmanid', $salesmanId))
            ->groupBy('salesorder.salesdate')
            ->pluck('total', 'tanggal');

        $diffInDays = $startDate->diffInDays($endDate);
        $labels = [];
        $data = [];

        for ($i = $diffInDays; $i >= 0; $i--) {
            $carbonDate = Carbon::parse($endDate)->subDays($i);
            $date = $carbonDate->toDateString();

            // Paksa locale ke 'id' untuk bahasa Indonesia
            $labels[] = $carbonDate->locale('id')->translatedFormat('l');

            $data[] = isset($chart_order[$date]) ? (float) $chart_order[$date] : 0.0;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * @OA\Get(
     *     path="/dashboard/kasir-today",
     *     tags={"Dashboard"},
     *     summary="Dashboard Kasir - Ringkasan Hari Ini",
     *     description="Mengambil omset, retur, kas di tangan, dan biaya hari ini berdasarkan usercreate kasir yang login.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(name="usercreate", in="query", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Sukses"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function kasirDashboard(Request $request)
    {
        $userCreate = $request->query('usercreate');
        $today = Carbon::today();

        // 1. Omset Penjualan Hari Ini (kind=0, berdasarkan usercreate kasir)
        $omset_kotor_hari_ini = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->whereDate('sales.salesdate', $today)
            ->where('sales.kind', 0)
            ->when($userCreate, fn($q) => $q->where('sales.usercreate', $userCreate))
            ->sum('salesdetail.netamount');

        $diskon_hari_ini = DB::table('sales')
            ->whereDate('sales.salesdate', $today)
            ->where('sales.kind', 0)
            ->when($userCreate, fn($q) => $q->where('sales.usercreate', $userCreate))
            ->sum('sales.salesvaluedisc');

        $omset_hari_ini = $omset_kotor_hari_ini - $diskon_hari_ini;

        // 2. Nilai Retur Hari Ini (kind=1, berdasarkan usercreate kasir)
        $retur_hari_ini = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->whereDate('sales.salesdate', $today)
            ->where('sales.kind', 1)
            ->when($userCreate, fn($q) => $q->where('sales.usercreate', $userCreate))
            ->sum('salesdetail.netamount');

        // 3. Kas di Tangan Hari Ini
        // = SUM debit dari journaltrans akun Kas (101.001) yang dibuat oleh kasir hari ini
        // Debit = uang masuk ke kas, Credit = uang keluar dari kas
        // $kas_di_tangan = DB::table('journaltrans')
        //     ->whereDate('jtdate', $today)
        //     ->whereIn('accountid', ['101.001', '101.003'])
        //     ->when($userCreate, fn($q) => $q->where('usercreate', $userCreate))
        //     ->selectRaw('SUM(debit - credit) as total')
        //     ->value('total') ?? 0;

        $kas_di_tangan = DB::table('journaltrans')
            ->join('journal', 'journaltrans.jtid', '=', 'journal.jtid')
            ->whereDate('journal.jtdate', '<=', $today) // Gunakan akumulasi waktu untuk Saldo/Balance
            ->where('journaltrans.accountid', '101.002')
            ->where('journal.approved', 1)
            ->sum(DB::raw('journaltrans.debit - journaltrans.credit'));
        // 4. Biaya Hari Ini
        // = SUM debit dari journaltrans akun biaya (6xx) yang dibuat oleh kasir hari ini
        $biaya_hari_ini = DB::table('journaltrans')
            ->whereDate('jtdate', $today)
            ->where('accountid', 'like', '6%')
            ->when($userCreate, fn($q) => $q->where('usercreate', $userCreate))
            ->sum('debit');

        return response()->json([
            'status' => 'success',
            'data' => [
                'date' => $today->toDateString(),
                'omset_hari_ini' => (float) $omset_hari_ini,
                'retur_hari_ini' => (float) $retur_hari_ini,
                'kas_di_tangan' => (float) $kas_di_tangan,
                'biaya_hari_ini' => (float) $biaya_hari_ini,
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/dashboard/gudang",
     *     tags={"Dashboard"},
     *     summary="Dashboard Gudang",
     *     description="Mengambil data low stock, barang retur fisik hari ini, dan barang expired/mendekati expired.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Response(response=200, description="Sukses"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function gudangDashboard()
    {
        $today = Carbon::today();

        // -------------------------------------------------------------
        // 1. LOW STOCK (Kritis: Stok <= Minimum Reorder)
        // -------------------------------------------------------------
        $queryLowStock = DB::table('product')
            ->leftJoin('inventory', 'product.id', '=', 'inventory.productid')
            ->select(
                'product.id as sku',
                'product.name',
                'product.minimumreorder',
                DB::raw('COALESCE(SUM(inventory.invin) - SUM(inventory.invout), 0) as stock')
            )
            ->where('product.isactive', 1)
            ->groupBy('product.id', 'product.name', 'product.minimumreorder')
            ->havingRaw('COALESCE(SUM(inventory.invin) - SUM(inventory.invout), 0) <= product.minimumreorder');

        // Menghitung total data dan mengambil Top 5 yang paling kritis (stok terendah)
        $totalLowStock = (clone $queryLowStock)->get()->count();
        $previewLowStock = $queryLowStock->orderBy('stock', 'asc')->limit(5)->get();

        // -------------------------------------------------------------
        // 2. MENDEKATI MIN REORDER (Stok > Min Reorder TAPI <= Min Reorder + 5)
        // -------------------------------------------------------------
        $queryApproachingReorder = DB::table('product')
            ->leftJoin('inventory', 'product.id', '=', 'inventory.productid')
            ->select(
                'product.id as sku',
                'product.name',
                'product.minimumreorder',
                DB::raw('COALESCE(SUM(inventory.invin) - SUM(inventory.invout), 0) as stock')
            )
            ->where('product.isactive', 1)
            ->where('product.minimumreorder', '>', 0) // Pastikan barang ini memang punya setting reorder
            ->groupBy('product.id', 'product.name', 'product.minimumreorder')
            // Logika: Stok sedikit di atas batas min reorder (batas aman sementara)
            ->havingRaw('COALESCE(SUM(inventory.invin) - SUM(inventory.invout), 0) > product.minimumreorder')
            ->havingRaw('COALESCE(SUM(inventory.invin) - SUM(inventory.invout), 0) <= (product.minimumreorder + 5)');

        $totalApproachingReorder = (clone $queryApproachingReorder)->get()->count();
        $previewApproachingReorder = $queryApproachingReorder->orderBy('stock', 'asc')->limit(5)->get();

        // -------------------------------------------------------------
        // 3. BARANG RETUR FISIK HARI INI (Biasanya tidak banyak, ambil semua/limit 20)
        // -------------------------------------------------------------
        $retur_hari_ini = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->join('product', 'salesdetail.productid', '=', 'product.id')
            ->select(
                'sales.salesid',
                'product.id as sku',
                'product.name',
                'salesdetail.returnqty',
                'salesdetail.unit',
                'sales.memo as alasan_retur'
            )
            ->whereDate('sales.salesdate', $today)
            ->where('sales.kind', 1) // 1 = Retur
            ->orderBy('sales.salesid', 'desc')
            ->limit(20) // Maksimal 20 di dashboard
            ->get();

        // -------------------------------------------------------------
        // 4. STOCK EXPIRED (Sudah Kadaluarsa: <= Hari Ini)
        // -------------------------------------------------------------
        $queryExpired = DB::table('inventory')
            ->join('product', 'inventory.productid', '=', 'product.id')
            ->select(
                'product.id as sku',
                'product.name',
                'inventory.expireddate',
                DB::raw('SUM(inventory.invin) - SUM(inventory.invout) as sisa_stok')
            )
            ->whereNotNull('inventory.expireddate')
            ->whereDate('inventory.expireddate', '<', $today)
            ->groupBy('product.id', 'product.name', 'inventory.expireddate')
            ->havingRaw('SUM(inventory.invin) - SUM(inventory.invout) > 0');

        $totalExpired = (clone $queryExpired)->get()->count();
        $previewExpired = $queryExpired->orderBy('inventory.expireddate', 'asc')->limit(5)->get();

        // -------------------------------------------------------------
        // 5. EXPIRING SOON (Akan Kadaluarsa: Hari Ini s/d H+30)
        // -------------------------------------------------------------
        $queryExpiringSoon = DB::table('inventory')
            ->join('product', 'inventory.productid', '=', 'product.id')
            ->select(
                'product.id as sku',
                'product.name',
                'inventory.expireddate',
                DB::raw('SUM(inventory.invin) - SUM(inventory.invout) as sisa_stok')
            )
            ->whereNotNull('inventory.expireddate')
            ->whereBetween('inventory.expireddate', [$today, $today->copy()->addDays(30)])
            ->groupBy('product.id', 'product.name', 'inventory.expireddate')
            ->havingRaw('SUM(inventory.invin) - SUM(inventory.invout) > 0');

        $totalExpiringSoon = (clone $queryExpiringSoon)->get()->count();
        $previewExpiringSoon = $queryExpiringSoon->orderBy('inventory.expireddate', 'asc')->limit(5)->get();

        // -------------------------------------------------------------
        // 6. PRODUK TERLARIS BULAN INI (Berdasarkan Qty Terjual)
        // -------------------------------------------------------------
        $startOfMonth = $today->copy()->startOfMonth();
        $topProducts = $this->getTopProductsData([$startOfMonth, $today]);

        // =============================================================
        // FINAL RESPONSE (Optimized)
        // =============================================================
        return response()->json([
            'status' => 'success',
            'data' => [
                'date' => $today->format('d-m-Y'),

                'low_stock' => [
                    'total_items' => $totalLowStock,
                    'preview' => $previewLowStock
                ],
                'approaching_reorder' => [
                    'total_items' => $totalApproachingReorder,
                    'preview' => $previewApproachingReorder
                ],
                'retur_hari_ini' => [
                    'total_items' => $retur_hari_ini->count(), // Diasumsikan retur harian < 20
                    'preview' => $retur_hari_ini
                ],
                'expired' => [
                    'total_items' => $totalExpired,
                    'preview' => $previewExpired
                ],
                'expiring_soon' => [
                    'total_items' => $totalExpiringSoon,
                    'preview' => $previewExpiringSoon
                ],
                'top_products_bulan_ini' => $topProducts
            ]
        ]);
    }

    private function getSalesAndOrderChart($startDate, $endDate)
    {
        $sales = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->select('sales.salesdate as tanggal', DB::raw('SUM(salesdetail.netamount) as total'))
            ->where('sales.kind', 0)
            ->whereBetween('sales.salesdate', [$startDate, $endDate])
            ->groupBy('sales.salesdate')
            ->pluck('total', 'tanggal');

        $orders = DB::table('salesorderdetail')
            ->join('salesorder', 'salesorderdetail.salesid', '=', 'salesorder.salesid')
            ->select('salesorder.salesdate as tanggal', DB::raw('SUM(salesorderdetail.netamount) as total'))
            ->whereBetween('salesorder.salesdate', [$startDate, $endDate])
            ->groupBy('salesorder.salesdate')
            ->pluck('total', 'tanggal');

        $labels = [];
        $salesData = [];
        $orderData = [];

        $diffInDays = $startDate->diffInDays($endDate);
        for ($i = $diffInDays; $i >= 0; $i--) {
            $carbonDate = Carbon::parse($endDate)->subDays($i);
            $date = $carbonDate->toDateString();
            $labels[] = $carbonDate->locale('id')->translatedFormat('l');
            $salesData[] = (float) ($sales[$date] ?? 0);
            $orderData[] = (float) ($orders[$date] ?? 0);
        }

        return ['labels' => $labels, 'penjualan' => $salesData, 'order' => $orderData];
    }

    private function getDateRangeFromRequest(Request $request)
    {
        if ($request->has('start_date') && $request->has('end_date')) {
            return [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ];
        }
        return $this->getDateRange($request->query('period', 'today'));
    }

    /**
     * Helper untuk menghitung persentase pertumbuhan dibanding periode sebelumnya
     */
    private function calculateGrowth($current, $previous)
    {
        $growth = [];
        foreach (['omzet', 'laba', 'total_invoice'] as $key) {
            $prevVal = $previous[$key] ?? 0;
            $currVal = $current[$key] ?? 0;

            if ($prevVal == 0) {
                $growth[$key] = $currVal > 0 ? 100 : 0;
            } else {
                $growth[$key] = round((($currVal - $prevVal) / $prevVal) * 100, 1);
            }
        }
        return $growth;
    }
}
