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
        
        $barang = Barang::when($keyword, function($query, $keyword) {
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
        return view('admin.barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barang,kode_barang|max:20',
            'barcode' => 'nullable|unique:barang,barcode|max:50',
            'nama_barang' => 'required|max:100',
            'kategori' => 'nullable|max:50',
            'satuan' => 'required|max:20',
            'harga_beli_terakhir' => 'required|numeric|min:0',
            'harga_jual_normal' => 'required|numeric|min:0',
            'stok_sekarang' => 'required|numeric',
        ]);

        Barang::create($request->all());

        return redirect()->route('admin.barang.index')
                         ->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.barang.edit', compact('barang'));
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
            'satuan' => 'required|max:20',
            'harga_beli_terakhir' => 'required|numeric|min:0',
            'harga_jual_normal' => 'required|numeric|min:0',
            'stok_sekarang' => 'required|numeric',
            // Ignore unique check for current record
            'barcode' => 'nullable|max:50|unique:barang,barcode,' . $id . ',kode_barang',
        ]);

        $barang->update($request->all());

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

    public function search(Request $request) {
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
