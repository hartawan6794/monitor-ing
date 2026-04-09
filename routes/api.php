<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InventoryMovingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DynamicConnectionController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\InventoryAdjustmentController;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\Api\ReportController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/server/check', [DynamicConnectionController::class, 'checkServer']);


// Route Public (Koneksi Database tetap perlu di-switch lewat Header)
Route::middleware(['force.json', 'database.switch'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// Route Terproteksi (Login Diperlukan)
Route::middleware(['force.json', 'database.switch', 'auth:sanctum'])->group(function () {

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/low-stock-alert', [ProductController::class, 'lowStockAlert']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/config', [UserController::class, 'getUserConfig']);
    Route::post('/users/config', [UserController::class, 'updateBulkConfig']);

    // Master Data Dropdown
    Route::get('/master/divisions', [MasterDataController::class, 'divisions']);
    Route::get('/master/departments', [MasterDataController::class, 'departments']);
    Route::get('/master/suppliers', [MasterDataController::class, 'suppliers']);
    Route::get('/master/product-groups', [MasterDataController::class, 'productGroups']);
    Route::get('/master/product-brands', [MasterDataController::class, 'productBrands']);
    Route::get('/master/accounts', [MasterDataController::class, 'accounts']);
    Route::get('/master/user-config-rules', [MasterDataController::class, 'userConfigRules']);

    // Laporan Stok
    Route::get('/report/stock', [ReportController::class, 'stockReport']);
    Route::get('/report/history', [ReportController::class, 'stockHistory']);
    Route::get('/report/in-out', [ReportController::class, 'inOutReport']);
    Route::get('/report/adjust', [ReportController::class, 'adjustReport']);

    // Transaksi Adjustment
    Route::post('/inventory/adjust', [InventoryAdjustmentController::class, 'storeAdjustment']);
    Route::post('/inventory/move', [InventoryMovingController::class, 'storeMoving']);

    // Grup Admin (Hanya untuk User dengan Role 'admin' di usersconfig)
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'getDashboardData']);
        Route::get('/salesman', [DashboardController::class, 'getDailySalesBySalesman']);
        Route::get('/salesman/yearly', [DashboardController::class, 'getYearlySalesBySalesman']);
        Route::get('/summary', [DashboardController::class, 'summary']);
        Route::get('/top-products', [DashboardController::class, 'topProducts']);
        Route::get('/top-salesmen', [DashboardController::class, 'topSalesmen']);
        Route::get('/chart', [DashboardController::class, 'revenueChart']);
    });
});

