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
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
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
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
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
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
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
     *     @OA\Parameter(ref="#/components/parameters/X-Server-IP"),
     *     @OA\Parameter(ref="#/components/parameters/X-Database-Name"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Username"),
     *     @OA\Parameter(ref="#/components/parameters/X-DB-Password"),
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
            ->select('product.id', 'product.name', DB::raw('SUM(salesdetail.salesqty) as total_qty'))
            ->whereBetween('salesdetail.transdate', $dates)
            ->groupBy('product.id', 'product.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get()
            ->map(function ($item, $index) {
                return [
                    'rank' => $index + 1,
                    'id' => $item->id, // Tambahan untuk referensi
                    'title' => $item->name,
                    'qty' => $item->total_qty, // Angka asli
                    'subtitle' => number_format($item->total_qty, 0, ',', '.') . ' Terjual'
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
            ->select('salesman.name', DB::raw('SUM(salespayments.debit) as omzet'))
            ->whereBetween('salespayments.transdate', $dates)
            ->groupBy('salesman.id', 'salesman.name')
            ->orderByDesc('omzet')
            ->limit(5)
            ->get()
            ->map(function ($item, $index) {
                return [
                    'rank' => $index + 1,
                    'title' => $item->name,
                    'subtitle' => 'Omzet: Rp ' . number_format($item->omzet, 0, ',', '.')
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
}