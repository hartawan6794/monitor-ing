<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthorizedServerController;
use App\Http\Controllers\AvailableDatabaseController;
use App\Http\Controllers\ConnectionWebController;
use App\Http\Controllers\PricingPlanController;

Route::get('/', [App\Http\Controllers\LandingPageController::class, 'index'])->name('landing');

Route::get('users/data', [UserController::class, 'getData'])->name('users.data');
Route::resource('user', UserController::class);

Route::get('authorized_server/data', [AuthorizedServerController::class, 'getData'])->name('authorized_server.data');
Route::resource('authorized_server', AuthorizedServerController::class);

Route::get('available_database/data', [AvailableDatabaseController::class, 'getData'])->name('available_database.data');
Route::resource('available_database', AvailableDatabaseController::class);
Route::get('/available-database/manage/{serverId}', [AvailableDatabaseController::class, 'manage'])->name('available_database.manage');
Route::post('/available-database/sync/{serverId}', [AvailableDatabaseController::class, 'sync'])->name('available_database.sync');
Route::post('/available-database/restore/{id}', [AvailableDatabaseController::class, 'restore'])->name('available_database.restore');

Route::get('pricing_plan/data', [PricingPlanController::class, 'getData'])->name('pricing_plan.data');
Route::resource('pricing_plan', PricingPlanController::class);

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
// Route::get('/', [App\Http\Controllers\LandingPageController::class,'index'])->name('landing');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Tambahkan di dalam grup route yang menggunakan auth middleware
Route::get('/get-databases-by-server/{serverId}', [App\Http\Controllers\AvailableDatabaseController::class, 'fetchDatabasesFromServer'])->name('server.databases');

// ── Koneksi portable (via web session, tidak butuh API token) ──
Route::post('/connections/test', [ConnectionWebController::class, 'test'])->name('connections.test')->middleware('auth');

// ── Setup Wizard ──
Route::middleware('auth')->group(function () {
    Route::get('/setup-wizard', [\App\Http\Controllers\SetupWizardController::class, 'index'])->name('setup.wizard');
    Route::post('/setup-wizard/step-user', [\App\Http\Controllers\SetupWizardController::class, 'storeUser'])->name('setup.wizard.user');
    Route::post('/setup-wizard/step-server', [\App\Http\Controllers\SetupWizardController::class, 'storeServer'])->name('setup.wizard.server');
    Route::post('/setup-wizard/step-database', [\App\Http\Controllers\SetupWizardController::class, 'storeDatabase'])->name('setup.wizard.database');
});

// ── Kelola Langganan ──
Route::middleware('auth')->prefix('subscriptions')->name('subscriptions.')->group(function () {
    Route::get('/', [\App\Http\Controllers\SubscriptionController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\SubscriptionController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\SubscriptionController::class, 'store'])->name('store');
    Route::post('/{id}/renew', [\App\Http\Controllers\SubscriptionController::class, 'renew'])->name('renew');
    Route::post('/{id}/send-reminder', [\App\Http\Controllers\SubscriptionController::class, 'sendReminder'])->name('sendReminder');
});

// ── System Admin ──
Route::middleware('auth')->prefix('system')->name('system.')->group(function () {
    Route::get('/apk-manager', [\App\Http\Controllers\ApkManagerController::class, 'index'])->name('apk_manager');
    Route::post('/apk-manager/upload', [\App\Http\Controllers\ApkManagerController::class, 'upload'])->name('apk_manager.upload');
    
    Route::get('/logs', [\App\Http\Controllers\SystemAdminController::class, 'systemLogs'])->name('logs');
    Route::post('/logs/clear', [\App\Http\Controllers\SystemAdminController::class, 'clearLogs'])->name('logs.clear');
    Route::get('/access-keys', [\App\Http\Controllers\SystemAdminController::class, 'accessKeys'])->name('access_keys');
});
