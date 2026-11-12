<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\StockInController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::resource('produk', ProductController::class)
        ->except('show')
        ->names([
            'index' => 'products.index',
            'create' => 'products.create',
            'store' => 'products.store',
            'edit' => 'products.edit',
            'update' => 'products.update',
            'destroy' => 'products.destroy',
        ]);

    Route::get('/barang-masuk', [StockInController::class, 'index'])->name('stock-in.index');
    Route::post('/barang-masuk', [StockInController::class, 'store'])->name('stock-in.store');
    Route::get('/barang-keluar', [StockOutController::class, 'index'])->name('stock-out.index');
    Route::post('/barang-keluar', [StockOutController::class, 'store'])->name('stock-out.store');
    Route::get('/laporan-stok', [StockReportController::class, 'index'])->name('stock-report.index');
});
