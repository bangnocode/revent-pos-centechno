<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $barang = Barang::when($keyword, function ($query, $keyword) {
            return $query->where('nama_barang', 'like', "%{$keyword}%")
                ->orWhere('kode_barang', 'like', "%{$keyword}%")
                ->orWhere('barcode', 'like', "%{$keyword}%");
        })
            ->orderBy('nama_barang', 'asc')
            ->paginate(10);

        return view('admin.barang.index', compact('barang', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $satuans = \App\Models\Satuan::aktif()->orderBy('nama_satuan')->get();
        $suppliers = \App\Models\Supplier::where('status_aktif', true)->orderBy('nama_supplier')->get();
        return view('admin.barang.create', compact('satuans', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'nullable|unique:barang,kode_barang|max:20',
            'barcode' => 'nullable|unique:barang,barcode|max:50',
            'nama_barang' => 'required|max:100',
            'kategori' => 'nullable|max:50',
            'satuan_id' => 'required|exists:satuans,id',
            'nama_supplier' => 'nullable|string|max:100',
            'harga_beli_terakhir' => 'required|numeric|min:0',
            'harga_jual_normal' => 'required|numeric|min:0',
            'stok_sekarang' => 'nullable|numeric',
        ], [
            'barcode.unique' => 'Barcode / SKU sudah digunakan',
            'kode_barang.unique' => 'Kode barang sudah digunakan',
            'nama_barang.required' => 'Nama barang wajib diisi',
            'satuan_id.required' => 'Satuan wajib dipilih',
            'harga_jual_normal.required' => 'Harga jual wajib diisi',
        ]);

        $data = $request->all();
        $data['stok_sekarang'] = $data['stok_sekarang'] ?? 0;

        // Generate sequential kode_barang if empty (Format: BRGXXXXXX)
        if (empty($data['kode_barang'])) {
            $lastBarang = Barang::where('kode_barang', 'like', 'BRG%')
                ->orderByRaw('LENGTH(kode_barang) DESC')
                ->orderBy('kode_barang', 'desc')
                ->first();

            if ($lastBarang) {
                // Ambil angka setelah 'BRG'
                $lastCode = $lastBarang->kode_barang;
                $lastNumber = intval(substr($lastCode, 3));
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            // Gabungkan BRG dengan angka yang di-pad 6 digit
            $data['kode_barang'] = 'BR' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

            // Pastikan tidak duplikat (safety check)
            while (Barang::where('kode_barang', $data['kode_barang'])->exists()) {
                $nextNumber++;
                $data['kode_barang'] = 'BR' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            }
        }

        // Generate barcode if empty (EAN-13 like numeric code)
        if (empty($data['barcode'])) {
            do {
                // Generate a random 12 digit number (timestamp + random digits)
                $barcode = $data['kode_barang'];
            } while (Barang::where('barcode', $barcode)->exists());

            $data['barcode'] = $data['kode_barang'];
        }

        // Set satuan field untuk backward compatibility
        $satuan = \App\Models\Satuan::find($request->satuan_id);
        $data['satuan'] = $satuan->nama_satuan;

        $barang = Barang::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil ditambahkan',
                'data' => $barang
            ]);
        }

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $satuans = \App\Models\Satuan::aktif()->orderBy('nama_satuan')->get();
        $suppliers = \App\Models\Supplier::where('status_aktif', true)->orderBy('nama_supplier')->get();
        return view('admin.barang.edit', compact('barang', 'satuans', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|max:100',
            'kategori' => 'nullable|max:50',
            'satuan_id' => 'required|exists:satuans,id',
            'nama_supplier' => 'nullable|string|max:100',
            'harga_jual_normal' => 'required|numeric|min:0',
            'stok_sekarang' => 'required|numeric',
            // Ignore unique check for current record
            'barcode' => 'nullable|max:50|unique:barang,barcode,' . $id . ',kode_barang',
        ]);

        $data = $request->except('harga_beli_terakhir');
        // Set satuan field untuk backward compatibility
        $satuan = \App\Models\Satuan::find($request->satuan_id);
        $data['satuan'] = $satuan->nama_satuan;

        $barang->update($data);

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil dihapus');
    }

    public function search(Request $request)
    {
        $keyword = trim($request->input('keyword', ''));
        if (empty($keyword)) return response()->json([]);

        $term = '%' . $keyword . '%';
        $barang = Barang::where('nama_barang', 'like', $term)
            ->orWhere('kode_barang', 'like', $term)
            ->orWhere('barcode', 'like', $term)
            ->limit(20)
            ->get();

        Log::info('Admin Barang Search', ['keyword' => $keyword, 'count' => $barang->count()]);

        return response()->json($barang);
    }
    public function cekStok(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|exists:barang,kode_barang',
            'stok_gudang' => 'required|numeric',
        ]);

        $barang = Barang::findOrFail($request->kode_barang);
        $stok_database = $barang->stok_sekarang;
        $stok_gudang = $request->stok_gudang;
        $selisih = $stok_gudang - $stok_database;

        $barang->update([
            'selisih_stok' => $selisih,
            'tanggal_cek_stok' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hasil cek stok berhasil disimpan untuk ' . $barang->nama_barang,
            'data' => [
                'selisih' => $selisih,
                'tanggal' => now()->format('d/m/Y H:i')
            ]
        ]);
    }

    public function updateStok($kode_barang)
    {
        $barang = Barang::findOrFail($kode_barang);

        if ($barang->selisih_stok == 0) {
            return redirect()->back()->with('info', 'Tidak ada selisih stok untuk diupdate');
        }

        $stok_sebelum = $barang->stok_sekarang;
        $selisih = $barang->selisih_stok;

        $barang->stok_sekarang += $selisih;
        $barang->selisih_stok = 0;
        $barang->save();

        // Log the inventory movement
        \App\Models\InventoryLog::create([
            'tanggal_log' => now(),
            'kode_barang' => $barang->kode_barang,
            'jenis_pergerakan' => 'opname',
            'jumlah_pergerakan' => $selisih,
            'stok_sebelum' => $stok_sebelum,
            'stok_sesudah' => $barang->stok_sekarang,
            'nomor_referensi' => 'OP-' . date('YmdHis'),
            'id_operator' => \Illuminate\Support\Facades\Auth::user()->username ?? 'admin',
            'keterangan' => 'Penyesuaian stok via opname'
        ]);

        return redirect()->back()->with('success', 'Stok barang ' . $barang->nama_barang . ' berhasil diupdate');
    }

    public function updateStokMassal()
    {
        $barangs = Barang::where('selisih_stok', '!=', 0)->get();
        $count = 0;

        foreach ($barangs as $barang) {
            $stok_sebelum = $barang->stok_sekarang;
            $selisih = $barang->selisih_stok;

            $barang->stok_sekarang += $selisih;
            $barang->selisih_stok = 0;
            $barang->save();

            // Log the inventory movement
            \App\Models\InventoryLog::create([
                'tanggal_log' => now(),
                'kode_barang' => $barang->kode_barang,
                'jenis_pergerakan' => 'opname',
                'jumlah_pergerakan' => $selisih,
                'stok_sebelum' => $stok_sebelum,
                'stok_sesudah' => $barang->stok_sekarang,
                'nomor_referensi' => 'OPM-' . date('YmdHis'),
                'id_operator' => \Illuminate\Support\Facades\Auth::user()->username ?? 'admin',
                'keterangan' => 'Penyesuaian stok via opname massal'
            ]);
            $count++;
        }

        if ($count == 0) {
            return redirect()->back()->with('info', 'Tidak ada selisih stok yang perlu diupdate');
        }

        return redirect()->back()->with('success', $count . ' barang berhasil diupdate stoknya');
    }
}
