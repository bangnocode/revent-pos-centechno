@extends('admin.layout.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Laporan Laba Rugi</h2>
        <p class="text-sm text-gray-500">Analisis keuntungan per barang terjual</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.transaksi.laba-rugi') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 text-sm">
            </div>
        </div>
        <div class="flex-1">
             <label class="block text-xs font-medium text-gray-600 mb-1">Cari Barang / Faktur</label>
             <div class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama barang, kode barang, atau nomor faktur..." 
                    class="w-full px-3 py-2 pl-9 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500 text-sm">
                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
             </div>
        </div>
        <div class="flex items-end">
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-sm h-[42px]">
                Filter Data
            </button>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold uppercase">Total Penjualan (Omset)</p>
        <h3 class="text-xl font-bold text-blue-600">Rp {{ number_format($summary['total_omset'], 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold uppercase">Total Modal (HPP)</p>
        <h3 class="text-xl font-bold text-gray-800">Rp {{ number_format($summary['total_modal'], 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold uppercase">Total Laba Bersih</p>
        <h3 class="text-xl font-bold {{ $summary['total_laba'] >= 0 ? 'text-green-600 bg-green-50/30' : 'text-red-600 bg-red-50/30' }}">Rp {{ number_format($summary['total_laba'], 0, ',', '.') }}</h3>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3">Barang & Transaksi</th>
                    <th class="px-4 py-3 text-right">Harga Beli</th>
                    <th class="px-4 py-3 text-right">Harga Jual</th>
                    <th class="px-4 py-3 text-center">Qty</th>
                    <th class="px-4 py-3 text-right">Diskon</th>
                    <th class="px-4 py-3 text-right">Subtotal</th>
                    <th class="px-4 py-3 text-right">Laba Rugi</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($details as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-4">
                        <div class="font-bold text-gray-900">{{ $item->nama_barang }}</div>
                        <div class="text-[10px] text-gray-500 mt-0.5">
                            <span class="bg-gray-100 px-1.5 py-0.5 rounded">{{ $item->kode_barang }}</span>
                            <span class="mx-1">|</span>
                            <span class="font-medium text-blue-600">{{ $item->nomor_faktur }}</span>
                            <span class="mx-1">|</span>
                            {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y H:i') }}
                        </div>
                    </td>
                    <td class="px-4 py-4 text-right text-gray-600">
                        Rp {{ number_format($item->harga_beli_saat_itu, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-4 text-right text-gray-600">
                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-4 text-center font-medium">
                        {{ number_format($item->jumlah, 0, ',', '.') }} <span class="text-[10px] text-gray-400 capitalize">{{ $item->satuan }}</span>
                    </td>
                    <td class="px-4 py-4 text-right text-red-500 italic">
                        {{ $item->diskon_item > 0 ? 'Rp ' . number_format($item->diskon_item, 0, ',', '.') : '-' }}
                    </td>
                    <td class="px-4 py-4 text-right font-bold text-gray-900">
                        Rp {{ number_format($item->subtotal_item, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-4 text-right font-bold {{ $item->margin >= 0 ? 'text-green-600 bg-green-50/30' : 'text-red-600 bg-red-50/30' }}">
                        Rp {{ number_format($item->margin, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-4 text-center">
                        <a href="{{ route('admin.transaksi.show', $item->nomor_faktur) }}" class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded text-[11px] font-medium transition-colors">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="font-medium">Tidak ada data laba rugi ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($details->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $details->appends(['start_date' => $startDate, 'end_date' => $endDate, 'search' => $search])->links() }}
    </div>
    @endif
</div>
@endsection
