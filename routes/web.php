<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockTransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('products', ProductController::class)->except(['destroy']);
    Route::patch('/products/{id}/toggle-status', [ProductController::class, 'toggleStatus'])
        ->name('products.toggle-status');

    Route::resource('categories', CategoryController::class);

    Route::post('/stock-transactions', [StockTransactionController::class, 'store'])
        ->name('stock-transactions.store');

    Route::get('/stock-transactions', [StockTransactionController::class, 'index'])
        ->name('stock-transactions.index');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity-logs.index');

    Route::post('/stock-adjustments', [StockAdjustmentController::class, 'store'])
        ->name('stock-adjustments.store');

    Route::get('/stock-adjustments', [StockAdjustmentController::class, 'index'])
        ->name('stock-adjustments.index');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
