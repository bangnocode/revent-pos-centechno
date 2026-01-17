@extends('admin.layout.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Pembelian</h2>
        <p class="text-sm text-gray-500">Data kulakan barang masuk</p>
    </div>
    <a href="{{ route('admin.pembelian.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Kulakan Baru
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.pembelian.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
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
             <label class="block text-xs font-medium text-gray-600 mb-1">Cari Nomor Faktur / Kode Barang</label>
             <div class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nomor faktur atau kode barang..." 
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

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Nomor Faktur</th>
                    <th class="px-6 py-3">Supplier</th>
                    <th class="px-6 py-3">Metode Pembayaran</th>
                    <th class="px-6 py-3 text-right">Total</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pembelians as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-gray-900">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $item->nomor_faktur }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->supplier->nama_supplier ?? '-' }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ ucfirst($item->metode_pembayaran ?? '-') }}</td>
                    <td class="px-6 py-4 text-right font-medium text-gray-900">
                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.pembelian.show', $item->id) }}" class="inline-flex items-center justify-center p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Detail">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <p class="font-medium">Belum ada riwayat pembelian</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pembelians->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $pembelians->links() }}
    </div>
    @endif
</div>
@endsection
