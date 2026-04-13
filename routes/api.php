<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InventoryMovingController;
use App\Http\Controllers\Api\SalesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DynamicConnectionController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\InventoryAdjustmentController;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ConnectionController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/server/check', [DynamicConnectionController::class, 'checkServer']);

// ── Connections: Tanpa database.switch (baca DB lokal) ──
Route::middleware(['force.json', 'auth:sanctum'])->group(function () {
    Route::get('/connections', [ConnectionController::class, 'index']);        // List semua server+database
    Route::post('/connections/test', [ConnectionController::class, 'test']);   // Test koneksi ke server klien
});


// Route Public (Koneksi Database tetap perlu di-switch lewat Header)
Route::middleware(['force.json', 'database.switch'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// Route Terproteksi (Login Diperlukan)
Route::middleware(['force.json', 'database.switch', 'auth:sanctum'])->group(function () {

    // Grup Admin (Hanya untuk User dengan Role 'admin' di usersconfig)
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'getDashboardData']);
        Route::get('/sales-view', [DashboardController::class, 'salesDashboard']);
        Route::get('/salesman', [DashboardController::class, 'getDailySalesBySalesman']);
        Route::get('/salesman/yearly', [DashboardController::class, 'getYearlySalesBySalesman']);
        Route::get('/summary', [DashboardController::class, 'summary']);
        Route::get('/top-products', [DashboardController::class, 'topProducts']);
        Route::get('/top-salesmen', [DashboardController::class, 'topSalesmen']);
        Route::get('/chart', [DashboardController::class, 'revenueChart']);
        Route::get('/chart-monthly', [DashboardController::class, 'monthlyChart']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/low-stock-alert', [ProductController::class, 'lowStockAlert']);
        Route::put('/{id}', [ProductController::class, 'update']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/config', [UserController::class, 'getUserConfig']);
        Route::post('/config', [UserController::class, 'updateBulkConfig']);
    });

    // Master Data Dropdown
    Route::prefix('master')->group(function () {
        Route::get('/divisions', [MasterDataController::class, 'divisions']);
        Route::get('/departments', [MasterDataController::class, 'departments']);
        Route::get('/suppliers', [MasterDataController::class, 'suppliers']);
        Route::get('/product-groups', [MasterDataController::class, 'productGroups']);
        Route::get('/product-brands', [MasterDataController::class, 'productBrands']);
        Route::get('/accounts', [MasterDataController::class, 'accounts']);
        Route::get('/user-config-rules', [MasterDataController::class, 'userConfigRules']);
        Route::get('/customer-groups', [MasterDataController::class, 'customerGroups']);
        Route::get('/customers', [MasterDataController::class, 'customers']);
    });


    // Laporan Stok
    Route::prefix('report')->group(function () {
        Route::get('/stock', [ReportController::class, 'stockReport']);
        Route::get('/history', [ReportController::class, 'stockHistory']);
        Route::get('/in-out', [ReportController::class, 'inOutReport']);
        Route::get('/adjust', [ReportController::class, 'adjustReport']);
    });

    // Transaksi Adjustment
    Route::prefix('inventory')->group(function () {
        Route::post('/adjust', [InventoryAdjustmentController::class, 'storeAdjustment']);
        Route::post('/move', [InventoryMovingController::class, 'storeMoving']);
    });


    Route::prefix('sales')->group(function () {
        Route::get('/history', [SalesController::class, 'salesHistory']);
        Route::get('/{salesid}/detail', [SalesController::class, 'salesDetail'])->where('salesid', '.*');
    });
});

