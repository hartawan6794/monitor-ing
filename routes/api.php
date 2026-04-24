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
use App\Http\Controllers\Api\CustomerController;


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
Route::middleware(['force.json', \App\Http\Middleware\DatabaseSwitcher::class, 'auth:sanctum'])->group(function () {

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
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::post('/', [CustomerController::class, 'store']);
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
        Route::post('/customers', [CustomerController::class, 'store']);
        Route::get('/company', [MasterDataController::class, 'getCompany']);
        Route::get('/payment-types', [MasterDataController::class, 'getPaymentType']);
        Route::get('/salesman', [MasterDataController::class, 'getSalesman']);
    });


    // Laporan Stok
    Route::prefix('report')->group(function () {
        Route::get('/stock', [ReportController::class, 'stockReport']);
        Route::get('/history', [ReportController::class, 'stockHistory']);
        Route::get('/in-out', [ReportController::class, 'inOutReport']);
        Route::get('/adjust', [ReportController::class, 'adjustReport']);
        Route::get('/transfer', [ReportController::class, 'transferReport']);
    });

    // Transaksi Adjustment
    Route::prefix('inventory')->group(function () {
        Route::post('/adjust', [InventoryAdjustmentController::class, 'storeAdjustment']);
        Route::post('/move', [InventoryMovingController::class, 'storeMoving']);
    });


    Route::prefix('sales')->group(function () {
        Route::get('/orders', [SalesController::class, 'getSalesOrders']);
        Route::get('/orders/{salesid}/detail', [SalesController::class, 'salesOrderDetail'])->where('salesid', '.*');
        Route::get('/history', [SalesController::class, 'salesHistory']);
        Route::get('/{salesid}/detail', [SalesController::class, 'salesDetail'])->where('salesid', '.*');
        Route::post('/order', [SalesController::class, 'storeSalesOrder']);
        Route::post('/store', [SalesController::class, 'storeSale']);
        Route::post('/return', [SalesController::class, 'storeSalesReturn']);
    });
});

