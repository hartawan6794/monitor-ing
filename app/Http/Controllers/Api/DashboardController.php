<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
}