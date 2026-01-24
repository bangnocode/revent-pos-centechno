<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Global stats
        $totalBarang = Barang::count();
        $totalTransaksi = TransaksiPenjualan::count();
        $totalStok = Barang::sum('stok_sekarang');

        // Today's stats
        $todayStart = date('Y-m-d') . ' 00:00:00';
        $todayEnd = date('Y-m-d') . ' 23:59:59';

        $transaksiHariIni = TransaksiPenjualan::whereBetween('tanggal_transaksi', [$todayStart, $todayEnd])->count();
        $omsetHariIni = TransaksiPenjualan::whereBetween('tanggal_transaksi', [$todayStart, $todayEnd])->sum('total_transaksi');

        $labaHariIni = \App\Models\DetailPenjualan::whereHas('transaksi', function ($q) use ($todayStart, $todayEnd) {
            $q->whereBetween('tanggal_transaksi', [$todayStart, $todayEnd]);
        })->sum('margin');

        return view('admin.dashboard', compact(
            'totalBarang',
            'totalTransaksi',
            'totalStok',
            'transaksiHariIni',
            'omsetHariIni',
            'labaHariIni'
        ));
    }
}
