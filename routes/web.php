<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthorizedServerController;
use App\Http\Controllers\AvailableDatabaseController;
use App\Http\Controllers\ConnectionWebController;

Route::get('/', function () {
    return view('home');
})->name('landing');

Route::get('users/data', [UserController::class, 'getData'])->name('users.data');
Route::resource('user', UserController::class);

Route::get('authorized_server/data', [AuthorizedServerController::class, 'getData'])->name('authorized_server.data');
Route::resource('authorized_server', AuthorizedServerController::class);

Route::get('available_database/data', [AvailableDatabaseController::class, 'getData'])->name('available_database.data');
Route::resource('available_database', AvailableDatabaseController::class);
Route::get('/available-database/manage/{serverId}', [AvailableDatabaseController::class, 'manage'])->name('available_database.manage');
Route::post('/available-database/sync/{serverId}', [AvailableDatabaseController::class, 'sync'])->name('available_database.sync');
Route::post('/available-database/restore/{id}', [AvailableDatabaseController::class, 'restore'])->name('available_database.restore');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
// Route::get('/', [App\Http\Controllers\LandingPageController::class,'index'])->name('landing');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Tambahkan di dalam grup route yang menggunakan auth middleware
Route::get('/get-databases-by-server/{serverId}', [App\Http\Controllers\AvailableDatabaseController::class, 'fetchDatabasesFromServer'])->name('server.databases');

// ── Koneksi portable (via web session, tidak butuh API token) ──
Route::post('/connections/test', [ConnectionWebController::class, 'test'])->name('connections.test')->middleware('auth');
