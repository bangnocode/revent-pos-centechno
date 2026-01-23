<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
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
            'barcode' => 'required|unique:barang,barcode|max:50',
            'nama_barang' => 'required|max:100',
            'kategori' => 'nullable|max:50',
            'satuan_id' => 'required|exists:satuans,id',
            'nama_supplier' => 'nullable|string|max:100',
            'harga_beli_terakhir' => 'required|numeric|min:0',
            'harga_jual_normal' => 'required|numeric|min:0',
            'stok_sekarang' => 'nullable|numeric',
        ], [
            'barcode.required' => 'Barcode / SKU wajib diisi',
            'barcode.unique' => 'Barcode / SKU sudah digunakan',
            'kode_barang.unique' => 'Kode barang sudah digunakan',
            'nama_barang.required' => 'Nama barang wajib diisi',
            'satuan_id.required' => 'Satuan wajib dipilih',
            'harga_jual_normal.required' => 'Harga jual wajib diisi',
        ]);

        $data = $request->all();
        $data['stok_sekarang'] = $data['stok_sekarang'] ?? 0;

        // Generate random kode_barang if empty
        if (empty($data['kode_barang'])) {
            do {
                $randomCode = strtoupper(Str::random(8));
            } while (Barang::where('kode_barang', $randomCode)->exists());
            $data['kode_barang'] = $randomCode;
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
        $keyword = $request->input('keyword');
        if (empty($keyword)) return response()->json([]);

        $barang = Barang::where('nama_barang', 'like', "%{$keyword}%")
            ->orWhere('kode_barang', 'like', "%{$keyword}%")
            ->orWhere('barcode', 'like', "%{$keyword}%")
            ->limit(10)
            ->get();
        return response()->json($barang);
    }
}
