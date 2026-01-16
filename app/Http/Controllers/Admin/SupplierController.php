<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keyword = request()->input('keyword');
        $suppliers = Supplier::when($keyword, function ($query, $keyword) {
            return $query->where('nama_supplier', 'like', "%{$keyword}%")
                         ->orWhere('kontak_person', 'like', "%{$keyword}%");
        })->paginate(10);

        return view('admin.supplier.index', compact('suppliers', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|max:100',
            'kontak_person' => 'nullable|max:100',
            'telepon' => 'nullable|max:20',
            'alamat' => 'nullable|string',
            'status_aktif' => 'boolean',
        ]);

        Supplier::create($validated);

        return redirect()->route('admin.supplier.index')
                         ->with('success', 'Supplier berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('admin.supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|max:100',
            'kontak_person' => 'nullable|max:100',
            'telepon' => 'nullable|max:20',
            'alamat' => 'nullable|string',
            'status_aktif' => 'boolean',
        ]);

        $supplier->update($validated);

        return redirect()->route('admin.supplier.index')
                         ->with('success', 'Supplier berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        // Check if has dependencies
        if ($supplier->pembelians()->exists()) {
             return back()->with('error', 'Supplier tidak bisa dihapus karena memiliki riwayat pembelian.');
        }

        $supplier->delete();
        return redirect()->route('admin.supplier.index')
                         ->with('success', 'Supplier berhasil dihapus');
    }
}
