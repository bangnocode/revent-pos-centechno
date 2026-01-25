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

        // Monthly stats
        $monthStart = date('Y-m-01') . ' 00:00:00';
        $monthEnd = date('Y-m-t') . ' 23:59:59';
        $transaksiBulanIni = TransaksiPenjualan::whereBetween('tanggal_transaksi', [$monthStart, $monthEnd])->count();
        $omsetBulanIni = TransaksiPenjualan::whereBetween('tanggal_transaksi', [$monthStart, $monthEnd])->sum('total_transaksi');
        $labaBulanIni = \App\Models\DetailPenjualan::whereHas('transaksi', function ($q) use ($monthStart, $monthEnd) {
            $q->whereBetween('tanggal_transaksi', [$monthStart, $monthEnd]);
        })->sum('margin');

        // Yearly stats
        $yearStart = date('Y-01-01') . ' 00:00:00';
        $yearEnd = date('Y-12-31') . ' 23:59:59';
        $transaksiTahunIni = TransaksiPenjualan::whereBetween('tanggal_transaksi', [$yearStart, $yearEnd])->count();
        $omsetTahunIni = TransaksiPenjualan::whereBetween('tanggal_transaksi', [$yearStart, $yearEnd])->sum('total_transaksi');
        $labaTahunIni = \App\Models\DetailPenjualan::whereHas('transaksi', function ($q) use ($yearStart, $yearEnd) {
            $q->whereBetween('tanggal_transaksi', [$yearStart, $yearEnd]);
        })->sum('margin');

        return view('admin.dashboard', compact(
            'totalBarang',
            'totalTransaksi',
            'totalStok',
            'transaksiHariIni',
            'omsetHariIni',
            'labaHariIni',
            'transaksiBulanIni',
            'omsetBulanIni',
            'labaBulanIni',
            'transaksiTahunIni',
            'omsetTahunIni',
            'labaTahunIni'
        ));
    }
}
