<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $satuans = Satuan::orderBy('nama_satuan')->paginate(15);
        return view('admin.satuan.index', compact('satuans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.satuan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:50|unique:satuans,nama_satuan',
            'singkatan' => 'nullable|string|max:10',
            'status_aktif' => 'boolean'
        ]);

        Satuan::create($request->all());

        return redirect()->route('admin.satuan.index')
            ->with('success', 'Satuan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Satuan $satuan)
    {
        return view('admin.satuan.show', compact('satuan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Satuan $satuan)
    {
        return view('admin.satuan.edit', compact('satuan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Satuan $satuan)
    {
        $request->validate([
            'nama_satuan' => ['required', 'string', 'max:50', Rule::unique('satuans')->ignore($satuan->id)],
            'singkatan' => 'nullable|string|max:10',
            'status_aktif' => 'boolean'
        ]);

        $satuan->update($request->all());

        return redirect()->route('admin.satuan.index')
            ->with('success', 'Satuan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Satuan $satuan)
    {
        // Cek apakah satuan masih digunakan
        if ($satuan->barangs()->count() > 0) {
            return redirect()->route('admin.satuan.index')
                ->with('error', 'Satuan tidak dapat dihapus karena masih digunakan oleh barang');
        }

        $satuan->delete();

        return redirect()->route('admin.satuan.index')
            ->with('success', 'Satuan berhasil dihapus');
    }
}
