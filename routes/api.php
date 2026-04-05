<?php

use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DynamicConnectionController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;

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
    Route::get('/dashboard', [DashboardController::class, 'getDashboardData']);
    Route::get('/dashboard/salesman', [DashboardController::class, 'getDailySalesBySalesman']);
    Route::get('/dashboard/salesman/yearly', [DashboardController::class, 'getYearlySalesBySalesman']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/low-stock', [ProductController::class, 'lowStockAlert']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);

    // Grup Admin (Hanya untuk User dengan Role 'admin' di usersconfig)
    Route::prefix('admin/dashboard')->middleware(['check.role:admin'])->group(function () {
        Route::get('/summary', [AdminDashboardController::class, 'summary']);
        Route::get('/top-products', [AdminDashboardController::class, 'topProducts']);
        Route::get('/top-salesmen', [AdminDashboardController::class, 'topSalesmen']);
        Route::get('/chart', [AdminDashboardController::class, 'revenueChart']);
    });
});

