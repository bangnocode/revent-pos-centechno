@extends('admin.layout.app')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 print:hidden">
        <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
             <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
             Laporan Buku Besar
        </h2>

        <form action="{{ route('admin.akuntansi.buku-besar') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs uppercase font-semibold text-gray-500 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full text-sm px-3 py-2 h-10 rounded border border-gray-200 focus:ring-2 focus:ring-blue-100 outline-none bg-gray-50/50">
            </div>

            <div>
                <label class="block text-xs uppercase font-semibold text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="w-full text-sm px-3 py-2 h-10 rounded border border-gray-200 focus:ring-2 focus:ring-blue-100 outline-none bg-gray-50/50">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs uppercase font-semibold text-gray-500 mb-1">Akun / Rekening</label>
                <select name="rekening_id" class="select2-rekening w-full">
                    <option value="">-- Semua Rekening --</option>
                    @foreach($rekeningList as $rek)
                    <option value="{{ $rek->id }}" {{ $koder == $rek->id ? 'selected' : '' }}>
                        {{ $rek->kode_rekening }} - {{ $rek->nama_rekening }} [{{ strtoupper($rek->tipe_rekening) }}]
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-4 flex justify-end gap-2 border-t border-gray-50 pt-2">
                <a href="{{ route('admin.akuntansi.buku-besar') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-200 transition-colors">
                    Reset
                </a>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2 tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden print:border-none print:shadow-none">
        <div class="p-6 bg-gray-50 border-b hidden print:block text-center">
            <h1 class="text-2xl font-bold uppercase">Laporan Buku Besar</h1>
            <p class="text-sm font-medium">{{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border-collapse">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No. Jurnal</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kode Akun</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Saldo Awal</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Debit</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Kredit</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($laporan as $row)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 font-mono">
                            {{ \Carbon\Carbon::parse($row->TGL)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs font-bold text-blue-600 font-mono">
                            {{ $row->NOSLIP }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="font-bold text-gray-800 text-sm">{{ $row->KODER }}</div>
                            <div class="text-[10px] font-semibold text-gray-400 truncate max-w-[200px] uppercase">{{ $row->NAMA_REKENING }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 leading-tight">
                            {{ $row->KET }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-mono text-gray-400">
                            {{ $row->SALDOAWAL != 0 ? number_format($row->SALDOAWAL, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-mono text-green-600 font-bold">
                            {{ $row->DEBIT != 0 ? number_format($row->DEBIT, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-mono text-red-600 font-bold">
                            {{ $row->KREDIT != 0 ? number_format($row->KREDIT, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-mono text-blue-600 font-bold">
                            {{ $row->SALDO != 0 ? number_format($row->SALDO, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-20 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center p-4">
                                <svg class="w-16 h-16 text-gray-100 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm font-bold uppercase tracking-widest leading-none">Tidak ada data ditemukan</p>
                                <p class="text-[10px] mt-1">Silakan sesuaikan filter tanggal atau rekening Anda</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="font-bold">
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-right text-xs uppercase tracking-tighter opacity-60">Total Periode Ini:</td>
                        <td class="px-4 py-4 text-right text-sm text-green-600 font-mono">
                            {{ number_format($laporan->sum('DEBIT'), 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-4 text-right text-sm text-red-600 font-mono">
                            {{ number_format($laporan->sum('KREDIT'), 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2-rekening').select2({
            placeholder: '--- Semua Rekening ---',
            allowClear: true,
            theme: 'default'
        });
    });
</script>

<style>
    @media print {
        @page { size: landscape; margin: 5mm; }
        body { background: white; padding: 0; }
        nav, aside, header, .print\:hidden { display: none !important; }
        .fixed { position: static !important; }
        .overflow-hidden { overflow: visible !important; }
    }
    
    /* Select2 Tweaks */
    .select2-container .select2-selection--single {
        height: 40px !important;
        display: flex !important;
        align-items: center !important;
        border-color: #e5e7eb !important;
        background-color: #f9fafb !important;
        font-size: 0.875rem !important;
    }
</style>
@endsection
