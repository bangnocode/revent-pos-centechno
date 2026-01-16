@extends('admin.layout.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Laporan Penjualan</h2>
        <p class="text-sm text-gray-500">Rekap transaksi dan pendapatan</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.transaksi.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
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
             <label class="block text-xs font-medium text-gray-600 mb-1">Cari Faktur / Pelanggan</label>
             <div class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nomor faktur atau nama pelanggan..." 
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
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold uppercase">Total Omset</p>
        <h3 class="text-xl font-bold text-blue-600">Rp {{ number_format($summary['total_omset'], 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold uppercase">Total Transaksi</p>
        <h3 class="text-xl font-bold text-gray-800">{{ $summary['jumlah_transaksi'] }}</h3>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold uppercase">Transaksi Lunas</p>
        <h3 class="text-xl font-bold text-green-600">{{ $summary['total_lunas'] }}</h3>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold uppercase">Belum Lunas / Hutang</p>
        <h3 class="text-xl font-bold text-red-600">{{ $summary['total_hutang'] }}</h3>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3">Faktur & Waktu</th>
                    <th class="px-6 py-3">Pelanggan</th>
                    <th class="px-6 py-3 text-right">Total Transaksi</th>
                    <th class="px-6 py-3 text-center">Pembayaran</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transaksi as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ $item->nomor_faktur }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-gray-900 font-medium">{{ $item->nama_pelanggan ?: 'Pelanggan Umum' }}</div>
                        <div class="text-xs text-gray-500">{{ $item->jumlah_item }} Barang</div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="font-bold text-gray-900">Rp {{ number_format($item->total_transaksi, 0, ',', '.') }}</div>
                         @if($item->kembalian < 0)
                            <div class="text-xs text-red-500 mt-0.5">Kurang: Rp {{ number_format(abs($item->kembalian), 0, ',', '.') }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 bg-gray-100 rounded text-xs font-semibold text-gray-600 uppercase">{{ $item->metode_pembayaran }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($item->status_pembayaran == 'lunas')
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">LUNAS</span>
                        @elseif($item->status_pembayaran == 'hutang')
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">HUTANG</span>
                        @else
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">{{ strtoupper($item->status_pembayaran) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.transaksi.show', $item->nomor_faktur) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded text-xs font-medium transition-colors">
                            Detail
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="font-medium">Tidak ada data transaksi ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($transaksi->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $transaksi->appends(['start_date' => $startDate, 'end_date' => $endDate, 'search' => $search])->links() }}
    </div>
    @endif
</div>
@endsection
