<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DynamicConnectionController;
use App\Http\Controllers\Api\DashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/server/check', [DynamicConnectionController::class, 'checkServer']);


// Route ini butuh Header X-Server-IP dan X-Database-Name dari Android
Route::middleware(['database.switch'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/dashboard', [DashboardController::class, 'getDashboardData']);
    Route::get('/dashboard/salesman', [DashboardController::class, 'getDailySalesBySalesman']);
    Route::get('/dashboard/salesman/yearly', [DashboardController::class, 'getYearlySalesBySalesman']);
});