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
        $todayStart = Carbon::today();
        $todayEnd = Carbon::now();
        
        $yesterdayStart = Carbon::yesterday();
        $yesterdayEnd = Carbon::yesterday()->endOfDay();
        
        $startOfWeek = Carbon::now()->startOfWeek();
        
        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();
        
        $startOfMonth = Carbon::now()->startOfMonth();
        
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        return response()->json([
            'status' => 'success',
            'data' => [
                'hari_ini' => $this->calculateMetrics($todayStart, $todayEnd),
                'kemarin' => $this->calculateMetrics($yesterdayStart, $yesterdayEnd),
                'minggu_ini' => $this->calculateMetrics($startOfWeek, $todayEnd),
                'minggu_lalu' => $this->calculateMetrics($startOfLastWeek, $endOfLastWeek),
                'bulan_ini' => $this->calculateMetrics($startOfMonth, $todayEnd),
                'bulan_lalu' => $this->calculateMetrics($startOfLastMonth, $endOfLastMonth),
            ]
        ]);
    }

    /**
     * Helper untuk menghitung Omzet, Laba, Margin per rentang waktu
     */
    private function calculateMetrics($startDate, $endDate)
    {
        // Omzet = Total Uang Masuk (Debit) dari Sales Payments
        $omzet = DB::table('salespayments')
            ->whereBetween('transdate', [$startDate, $endDate])
            ->sum('debit');

        // COGS (HPP) = Total Cost dari Sales Detail
        $cogs = DB::table('salesdetail')
            ->whereBetween('transdate', [$startDate, $endDate])
            ->sum('cogs');

        // Laba Kotor = Omzet - HPP
        $laba = $omzet - $cogs;

        // Margin = (Laba / Omzet) * 100%
        $margin = $omzet > 0 ? round(($laba / $omzet) * 100, 1) : 0;

        return [
            'omzet' => $omzet,
            'laba' => $laba,
            'margin' => $margin
        ];
    }

    public function topProducts(Request $request)
    {
        $dates = $this->getDateRange($request->query('period', 'today'));

        $products = DB::table('salesdetail')
            ->join('product', 'salesdetail.productid', '=', 'product.id')
            ->select('product.id', 'product.name', DB::raw('SUM(salesdetail.salesqty) as total_qty'), DB::raw('SUM(salesdetail.netamount) as total_net'))
            ->whereBetween('salesdetail.transdate', $dates)
            ->groupBy('product.id', 'product.name')
            ->orderByDesc('total_net')
            ->limit(5)
            ->get()
            ->map(function ($item, $index) {
                return [
                    'rank' => $index + 1,
                    'id' => $item->id,
                    'title' => $item->name,
                    'qty' => $item->total_qty,
                    'subtitle' => number_format($item->total_qty, 0, ',', '.') . ' Terjual',
                    // Fields for Web Dashboard
                    'product_name' => $item->name,
                    'total_qty' => $item->total_qty,
                    'total_net' => $item->total_net
                ];
            });

        return response()->json(['status' => 'success', 'data' => $products]);
    }

    /**
     * 3. GET TOP SALESMEN (Berdasarkan Total Omzet/Debit)
     * Menerima query param: ?period=today|month|year
     */
    public function topSalesmen(Request $request)
    {
        $dates = $this->getDateRange($request->query('period', 'today'));

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

    /**
     * 4. GET CHART DATA (Tren Pendapatan 7 Hari Terakhir)
     */
    public function revenueChart()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $chartData = DB::table('salespayments')
            ->select(DB::raw('DATE(transdate) as date'), DB::raw('SUM(debit) as total'))
            ->whereBetween('transdate', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(transdate)'))
            ->orderBy('date', 'asc')
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

        // 7. Penjualan 7 hari terakhir
        $chart_penjualan = DB::table('salesdetail')
            ->join('sales', 'salesdetail.salesid', '=', 'sales.salesid')
            ->select('sales.salesdate as tanggal', DB::raw('SUM(salesdetail.netamount) as total'))
            ->where('sales.kind', 0)
            ->whereBetween('sales.salesdate', [$startDate7, $today])
            ->groupBy('sales.salesdate')
            ->orderBy('sales.salesdate', 'asc')
            ->get();

        // Order 7 hari terakhir
        $chart_order = DB::table('salesorderdetail')
            ->join('salesorder', 'salesorderdetail.salesid', '=', 'salesorder.salesid')
            ->select('salesorder.salesdate as tanggal', DB::raw('SUM(salesorderdetail.netamount) as total'))
            ->whereBetween('salesorder.salesdate', [$startDate7, $today])
            ->groupBy('salesorder.salesdate')
            ->orderBy('salesorder.salesdate', 'asc')
            ->get();

        // Android FE GSON mengharapkan List<Double> (array primitif angka tunggal per indeks)
        // Kita perlu menyusunnya menjadi 7 angka pas sesuai urutan 7 hari ke belakang (misal H-6 s/d H-0)
        $penjualan_array = [];
        $order_array = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $penjualan_array[$date] = 0;
            $order_array[$date] = 0;
        }

        foreach ($chart_penjualan as $item) {
            $penjualan_array[$item->tanggal] = (float) $item->total;
        }
        foreach ($chart_order as $item) {
            $order_array[$item->tanggal] = (float) $item->total;
        }

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
                    'penjualan' => array_values($penjualan_array),
                    'order' => array_values($order_array)
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
}