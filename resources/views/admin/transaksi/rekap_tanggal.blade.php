@extends('admin.layout.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Rekap Penjualan Per Tanggal</h2>
        <p class="text-sm text-gray-500">Rekapitulasi total omset dan laba rugi harian</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.transaksi.rekap-tanggal') }}" method="GET" class="flex flex-col md:flex-row gap-4">
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
        <p class="text-xs text-gray-500 font-semibold uppercase">Total Omset</p>
        <h3 class="text-xl font-bold text-blue-600">Rp {{ number_format($summary['total_omset'], 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold uppercase">Total Barang Terjual</p>
        <h3 class="text-xl font-bold text-gray-800">{{ number_format($summary['total_qty'], 0, ',', '.') }} Item</h3>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 font-semibold uppercase">Total Laba Bersih</p>
        <h3 class="text-xl font-bold {{ $summary['total_laba'] >= 0 ? 'text-green-600' : 'text-red-600' }}">Rp {{ number_format($summary['total_laba'], 0, ',', '.') }}</h3>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3 text-center">Total Item Terjual</th>
                    <th class="px-4 py-3 text-center">Total Barang Terjual</th>
                    <th class="px-4 py-3 text-right">Total Omset</th>
                    <th class="px-4 py-3 text-right">Total Laba Rugi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rekap as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</div>
                    </td>
                    <td class="px-4 py-4 text-center font-bold text-blue-600">
                        {{ number_format($item->total_barang_terjual, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-4 text-center font-bold text-gray-800">
                        {{ number_format($item->total_baris_item, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-4 text-right font-semibold text-gray-600">
                        Rp {{ number_format($item->total_omset, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-4 text-right font-black {{ $item->total_laba >= 0 ? 'text-green-600 bg-green-50/30' : 'text-red-600 bg-red-50/30' }}">
                        Rp {{ number_format($item->total_laba, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="font-medium">Tidak ada data rekap ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($rekap->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $rekap->appends(['start_date' => $startDate, 'end_date' => $endDate])->links() }}
    </div>
    @endif
</div>
@endsection
