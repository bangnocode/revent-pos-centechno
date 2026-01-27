@extends('admin.layout.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Daftar Jurnal Umum</h2>
        <p class="text-sm text-gray-500">Log semua transaksi jurnal manual</p>
    </div>
    <a href="{{ route('admin.jurnal.create') }}" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Buat Jurnal Baru
    </a>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3">No Jurnal</th>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Keterangan</th>
                    <th class="px-6 py-3 text-right">Total Transaksi</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($jurnals as $jurnal)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-mono font-medium text-gray-900">{{ $jurnal->no_jurnal }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ date('d/m/Y', strtotime($jurnal->tanggal)) }}</td>
                    <td class="px-6 py-4 text-gray-600 max-w-md truncate">{{ $jurnal->keterangan }}</td>
                    <td class="px-6 py-4 text-right font-medium text-gray-900">
                        Rp {{ number_format($jurnal->detail_jurnals->sum('debit'), 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-xs text-gray-400">View (Pending)</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <p class="font-medium">Belum ada data jurnal</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($jurnals->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $jurnals->links() }}
    </div>
    @endif
</div>
@endsection
