<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use App\Models\Barang;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function stok(Request $request)
    {
        $kode_barang = $request->input('kode_barang');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $logs = null;
        $selectedBarang = null;

        if ($kode_barang) {
            $selectedBarang = Barang::where('kode_barang', $kode_barang)->first();

            $query = InventoryLog::where('kode_barang', $kode_barang);

            if ($start_date) {
                $query->whereDate('tanggal_log', '>=', $start_date);
            }

            if ($end_date) {
                $query->whereDate('tanggal_log', '<=', $end_date);
            }

            $logs = $query->orderBy('tanggal_log', 'desc')
                ->paginate(10)
                ->withQueryString();
        }

        return view('admin.laporan.stok', compact('logs', 'selectedBarang', 'kode_barang', 'start_date', 'end_date'));
    }
}
