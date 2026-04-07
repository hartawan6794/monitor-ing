<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * 1. GET SUMMARY (Omzet, Laba, Margin untuk 4 Periode)
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