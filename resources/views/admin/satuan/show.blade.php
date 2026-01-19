@extends('admin.layout.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.satuan.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Detail Satuan: {{ $satuan->nama_satuan }}</h2>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-end gap-3 mb-6">
        <a href="{{ route('admin.satuan.edit', $satuan) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Satuan
        </a>
        <a href="{{ route('admin.satuan.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            Kembali ke Daftar
        </a>
    </div>

    <!-- Detail Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6 pb-2 border-b border-gray-100">Informasi Satuan</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">ID Satuan</label>
                    <p class="text-sm text-gray-900 font-mono">{{ $satuan->id }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Nama Satuan</label>
                    <p class="text-sm text-gray-900">{{ $satuan->nama_satuan }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Singkatan</label>
                    <p class="text-sm text-gray-900">{{ $satuan->singkatan ?: '-' }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Status</label>
                    <div class="mt-1">
                        @if($satuan->status_aktif)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Nonaktif
                            </span>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Dibuat</label>
                    <p class="text-sm text-gray-900">{{ $satuan->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Terakhir Diupdate</label>
                    <p class="text-sm text-gray-900">{{ $satuan->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Barang yang Menggunakan Satuan Ini -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Barang yang Menggunakan Satuan Ini ({{ $satuan->barangs->count() }})</h3>
        </div>

        @if($satuan->barangs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3">Kode / Nama Barang</th>
                        <th class="px-6 py-3 text-center">Stok</th>
                        <th class="px-6 py-3 text-right">Harga Jual</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($satuan->barangs as $barang)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $barang->nama_barang }}</div>
                            <div class="text-xs text-gray-500 font-mono mt-0.5">{{ $barang->kode_barang }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $barang->stok_sekarang > 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ number_format($barang->stok_sekarang, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-medium text-gray-900">
                            Rp {{ number_format($barang->harga_jual_normal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-12 text-center text-gray-500">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="font-medium">Belum ada barang yang menggunakan satuan ini</p>
            <p class="text-sm text-gray-400 mt-1">Satuan ini belum digunakan oleh produk manapun</p>
        </div>
        @endif
    </div>
</div>
@endsection