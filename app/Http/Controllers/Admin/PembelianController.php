<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-d'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $search = $request->input('search');

        $query = Pembelian::query();

        // Filter by Date
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        // Search by Invoice or Customer
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_faktur', 'like', "%{$search}%")
                    ->orWhereHas('details', function ($q) use ($search) {
                        $q->where('kode_barang', 'like', "%{$search}%");
                    });
            });
        }

        $pembelians = $query->with(['supplier', 'user', 'details'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('admin.pembelian.index', compact('pembelians', 'startDate', 'endDate', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::where('status_aktif', true)->get();
        // Generate auto number for PO? e.g. PO-YYYYMMDD-XXXX
        $today = date('Ymd');
        $lastPo = Pembelian::whereDate('created_at', date('Y-m-d'))->latest()->first();
        $nextNo = $lastPo ? (int)substr($lastPo->nomor_faktur, -3) + 1 : 1;
        $nomorFaktur = 'PO-' . $today . '-' . str_pad($nextNo, 3, '0', STR_PAD_LEFT);

        return view('admin.pembelian.create', compact('suppliers', 'nomorFaktur'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'metode_pembayaran' => 'required',
            'tanggal' => 'required|date',
            'nomor_faktur' => 'required|unique:pembelians,nomor_faktur',
            'items' => 'required|array|min:1',
            'items.*.kode_barang' => 'required|exists:barang,kode_barang',
            'items.*.jumlah' => 'required|numeric|min:0.01',
            'items.*.harga_beli' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Create Header
                $pembelian = Pembelian::create([
                    'nomor_faktur' => $request->nomor_faktur,
                    'tanggal' => $request->tanggal,
                    'supplier_id' => $request->supplier_id,
                    'total_harga' => collect($request->items)->sum(fn($item) => $item['jumlah'] * $item['harga_beli']),
                    'status' => 'selesai', // Directly finished for now
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'keterangan' => $request->keterangan,
                    'user_id' => auth()->id(),
                ]);

                foreach ($request->items as $item) {
                    // 2. Create Detail
                    $subtotal = $item['jumlah'] * $item['harga_beli'];

                    DetailPembelian::create([
                        'pembelian_id' => $pembelian->id,
                        'kode_barang' => $item['kode_barang'],
                        'jumlah' => $item['jumlah'],
                        'harga_beli_satuan' => $item['harga_beli'],
                        'subtotal' => $subtotal,
                    ]);

                    // 3. Update Stock & Price
                    $barang = Barang::findOrFail($item['kode_barang']);
                    $stokSebelum = $barang->stok_sekarang;

                    $barang->stok_sekarang += $item['jumlah'];
                    $barang->harga_beli_terakhir = $item['harga_beli'];
                    $barang->tgl_stok_masuk = now()->toDateString(); // Set tgl_stok_masuk
                    $barang->nama_supplier = $pembelian->supplier->nama_supplier; // Set nama_supplier
                    $barang->save();

                    // 4. Log Inventory
                    InventoryLog::create([
                        'tanggal_log' => now(),
                        'kode_barang' => $item['kode_barang'],
                        'jenis_pergerakan' => 'pembelian',
                        'jumlah_pergerakan' => $item['jumlah'],
                        'stok_sebelum' => $stokSebelum,
                        'stok_sesudah' => $barang->stok_sekarang,
                        'nomor_referensi' => $pembelian->nomor_faktur,
                        'id_operator' => auth()->user()->username ?? 'admin', // Use username or fallback
                        'keterangan' => 'Pembelian dari ' . $pembelian->supplier->nama_supplier,
                    ]);
                }
            });

            return response()->json(['message' => 'Pembelian berhasil disimpan', 'redirect' => route('admin.pembelian.index')]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembelian $pembelian)
    {
        $pembelian->load(['supplier', 'details.barang', 'user']);
        return view('admin.pembelian.show', compact('pembelian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return abort(404); // Editing purchases is complex/not allowed typically
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembelian $pembelian)
    {
        // Cancel purchase? Revert stock?
        // For now preventing delete if completed
        return back()->with('error', 'Pembelian tidak dapat dihapus.');
    }
}
