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
            $query->where(function ($q) use ($search) {
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

    public function labaRugi(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-d'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $search = $request->input('search');

        $query = \App\Models\DetailPenjualan::with('transaksi')
            ->whereHas('transaksi', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_transaksi', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('detail_penjualan.nama_barang', 'like', "%{$search}%")
                    ->orWhere('detail_penjualan.kode_barang', 'like', "%{$search}%")
                    ->orWhere('detail_penjualan.nomor_faktur', 'like', "%{$search}%");
            });
        }

        $details = $query->join('transaksi_penjualan', 'detail_penjualan.nomor_faktur', '=', 'transaksi_penjualan.nomor_faktur')
            ->select('detail_penjualan.*', 'transaksi_penjualan.tanggal_transaksi')
            ->orderBy('transaksi_penjualan.tanggal_transaksi', 'desc')
            ->paginate(20);

        // Summary recalculation (using clone to not affect pagination)
        $summaryQuery = \App\Models\DetailPenjualan::whereHas('transaksi', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_transaksi', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        });

        if ($search) {
            $summaryQuery->where(function ($q) use ($search) {
                $q->where('detail_penjualan.nama_barang', 'like', "%{$search}%")
                    ->orWhere('detail_penjualan.kode_barang', 'like', "%{$search}%")
                    ->orWhere('detail_penjualan.nomor_faktur', 'like', "%{$search}%");
            });
        }

        $summary = [
            'total_omset' => $summaryQuery->sum('subtotal_item'),
            'total_laba' => $summaryQuery->sum('margin'),
        ];
        $summary['total_modal'] = $summary['total_omset'] - $summary['total_laba'];

        return view('admin.transaksi.laba_rugi', compact('details', 'startDate', 'endDate', 'search', 'summary'));
    }

    public function show($nomor_faktur)
    {
        $transaksi = TransaksiPenjualan::with('details')->where('nomor_faktur', $nomor_faktur)->firstOrFail();
        return view('admin.transaksi.show', compact('transaksi'));
    }
}
