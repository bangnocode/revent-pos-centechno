<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-d'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $search = $request->input('search');

        $query = TransaksiPenjualan::query();

        // Filter by Date
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_transaksi', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        // Search by Invoice or Customer
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nomor_faktur', 'like', "%{$search}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('id_operator', 'like', "%{$search}%");
            });
        }

        $transaksi = $query->orderBy('tanggal_transaksi', 'desc')->paginate(10);

        // Summary Stats for the filtered period
        $summary = [
            'total_omset' => $query->sum('total_transaksi'),
            'jumlah_transaksi' => $query->count(),
            'total_lunas' => $query->clone()->where('status_pembayaran', 'lunas')->count(),
            'total_hutang' => $query->clone()->where('status_pembayaran', 'hutang')->count(),
        ];

        return view('admin.transaksi.index', compact('transaksi', 'startDate', 'endDate', 'search', 'summary'));
    }

    public function show($nomor_faktur)
    {
        $transaksi = TransaksiPenjualan::with('details')->where('nomor_faktur', $nomor_faktur)->firstOrFail();
        return view('admin.transaksi.show', compact('transaksi'));
    }
}
