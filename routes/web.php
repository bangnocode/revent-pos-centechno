<?php

use App\Http\Controllers\PosController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('landing-page');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// POS Routes
Route::middleware(['guest.or.auth'])->group(function () {
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/simpan-transaksi', [PosController::class, 'simpanTransaksi'])->name('pos.simpan-transaksi');
    Route::get('/pos/print-invoice/{faktur}', [PosController::class, 'printInvoice']);
    Route::get('/pos/print-invoice-data/{faktur}', [PosController::class, 'printInvoiceData']);
});

// Route untuk cari barang tanpa auth
Route::post('/pos/cari-barang', [PosController::class, 'cariBarang'])->name('pos.cari-barang');

// Route test
Route::get('/test-barang', function () {
    $barang = \App\Models\Barang::first();
    return response()->json($barang);
});

// Admin Routes
Route::middleware(['guest.or.auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('barang/search', [\App\Http\Controllers\Admin\BarangController::class, 'search'])->name('barang.search');
    Route::resource('barang', \App\Http\Controllers\Admin\BarangController::class);

    // Transaksi / Laporan Routes
    Route::get('/transaksi', [\App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/laba-rugi', [\App\Http\Controllers\Admin\TransaksiController::class, 'labaRugi'])->name('transaksi.laba-rugi');
    Route::get('/transaksi/{nomor_faktur}', [\App\Http\Controllers\Admin\TransaksiController::class, 'show'])->name('transaksi.show');

    // Supplier & Kulakan
    Route::resource('supplier', \App\Http\Controllers\Admin\SupplierController::class);
    Route::resource('pembelian', \App\Http\Controllers\Admin\PembelianController::class);
    Route::resource('satuan', \App\Http\Controllers\Admin\SatuanController::class);
});
