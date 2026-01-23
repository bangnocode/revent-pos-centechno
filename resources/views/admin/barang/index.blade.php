@extends('admin.layout.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Daftar Barang</h2>
        <p class="text-sm text-gray-500">Kelola katalog produk toko anda</p>
    </div>
    @auth
    <a href="{{ route('admin.barang.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Barang
    </a>
    @endauth
</div>

<!-- Search & Filter Filter -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.barang.index') }}" method="GET" class="flex gap-2">
        <div class="relative flex-1">
            <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Cari nama barang, kode, atau barcode..." 
                class="w-full px-4 py-2 pl-10 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-100 outline-none transition-all text-sm">
            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-900 transition-colors">
            Cari
        </button>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-center">Barang / Kode</th>
                    <th class="px-6 py-3 text-center">Harga Jual</th>
                    <th class="px-6 py-3 text-center">Harga Beli</th>
                    <th class="px-6 py-3 text-center">Stok</th>
                    <th class="px-6 py-3 text-center">Nilai Stok</th>
                    <th class="px-6 py-3 text-center">Supplier</th>
                    <th class="px-6 py-3 text-center">Stok Masuk</th>
                    <th class="px-6 py-3 text-center">Stok Keluar</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($barang as $item)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">{{ $item->nama_barang }}</div>
                        <div class="text-xs text-gray-500 font-mono mt-0.5">{{ $item->kode_barang }} <span class="text-gray-300">|</span> {{ $item->barcode }}</div>
                    </td>
                    <td class="px-6 py-4 text-right font-medium text-gray-900">
                        Rp {{ number_format($item->harga_jual_normal, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-right font-medium text-gray-900">
                        Rp {{ number_format($item->harga_beli_terakhir, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->stok_sekarang > 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ number_format($item->stok_sekarang, 0, ',', '.') }} {{ $item->satuan }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-medium text-gray-900">
                        Rp {{ number_format($item->harga_beli_terakhir * $item->stok_sekarang, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($item->nama_supplier)
                            <span class="text-sm text-gray-700">{{ $item->nama_supplier }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($item->tgl_stok_masuk)
                            <span class="text-sm text-gray-700">{{ date('d/m/Y', strtotime($item->tgl_stok_masuk)) }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($item->tgl_stok_keluar)
                            <span class="text-sm text-gray-700">{{ date('d/m/Y', strtotime($item->tgl_stok_keluar)) }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @auth
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.barang.edit', $item->kode_barang) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('admin.barang.destroy', $item->kode_barang) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        @else
                        <span class="text-gray-400 text-sm">Login untuk aksi</span>
                        @endauth
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="font-medium">Belum ada data barang</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($barang->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $barang->links() }}
    </div>
    @endif
</div>
@endsection
