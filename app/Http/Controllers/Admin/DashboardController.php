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
        // Simple stats
        $totalBarang = Barang::count();
        $totalTransaksi = TransaksiPenjualan::count();
        $totalStok = Barang::sum('stok_sekarang');

        return view('admin.dashboard', compact('totalBarang', 'totalTransaksi', 'totalStok'));
    }
}
