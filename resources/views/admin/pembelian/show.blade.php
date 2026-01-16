@extends('admin.layout.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Pembelian</h2>
            <p class="text-sm text-gray-500">Nomor Faktur: <span class="font-mono text-gray-700">{{ $pembelian->nomor_faktur }}</span></p>
        </div>
        <a href="{{ route('admin.pembelian.index') }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-lg text-sm font-medium transition-colors">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-gray-100">
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Informasi Supplier</h3>
                <div class="font-medium text-gray-900">{{ $pembelian->supplier->nama_supplier }}</div>
                <div class="text-gray-500 text-sm mt-1">{{ $pembelian->supplier->alamat ?? '-' }}</div>
                <div class="text-gray-500 text-sm mt-1">Telp: {{ $pembelian->supplier->telepon ?? '-' }}</div>
            </div>
            <div class="md:text-right">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Detail Transaksi</h3>
                <div class="text-sm text-gray-600">Tanggal: <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($pembelian->tanggal)->format('d F Y, H:i') }}</span></div>
                <div class="text-sm text-gray-600 mt-1">Status: <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 capitalize">{{ $pembelian->status }}</span></div>
                <div class="text-sm text-gray-600 mt-1">Operator: <span class="font-medium text-gray-900">{{ $pembelian->user->name ?? 'System' }}</span></div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3">Barang</th>
                        <th class="px-6 py-3 text-center">Jumlah</th>
                        <th class="px-6 py-3 text-right">Harga Beli</th>
                        <th class="px-6 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($pembelian->details as $detail)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $detail->barang->nama_barang ?? $detail->kode_barang }}</div>
                            <div class="text-xs text-gray-500 font-mono">{{ $detail->kode_barang }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">{{ $detail->jumlah }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($detail->harga_beli_satuan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-medium">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-900">Total Pembelian</td>
                        <td class="px-6 py-4 text-right font-bold text-blue-600 text-lg">Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
