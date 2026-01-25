@extends('admin.layout.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Laporan Keluar Masuk Barang</h2>
        <p class="text-sm text-gray-500">Pantau pergerakan stok barang anda secara detail</p>
    </div>
</div>

<!-- Pencarian Barang -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4">
    <form action="{{ route('admin.laporan.stok') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div class="md:col-span-2 space-y-1">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Cari Barang</label>
            <div class="relative group">
                <input type="text" id="barangSearchInput" name="keyword" value="{{ $keyword }}" placeholder="Ketik nama barang atau barcode..." 
                    class="w-full px-3 py-2 pl-9 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-xs font-medium">
                <div class="absolute left-3 top-2 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div id="searchDropdown" class="absolute z-30 w-full mt-1 bg-white rounded-lg shadow-xl border border-gray-100 overflow-hidden hidden divide-y divide-gray-50 max-h-60 overflow-y-auto">
                </div>
            </div>
            <input type="hidden" name="kode_barang" id="kodeBarangInput" value="{{ $kode_barang }}">
        </div>

        <div class="space-y-1">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Mulai</label>
            <input type="date" name="start_date" value="{{ $start_date }}" 
                class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-xs">
        </div>

        <div class="space-y-1">
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Sampai</label>
            <div class="flex gap-2">
                <input type="date" name="end_date" value="{{ $end_date }}" 
                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-xs">
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white font-bold rounded-lg hover:bg-slate-900 transition-all flex items-center justify-center gap-2 text-xs shrink-0">
                    CARI
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Info Barang Terpilih -->
@if($selectedBarang)
<div class="bg-blue-600 rounded-xl shadow-lg p-4 mb-4 text-white flex justify-between items-center">
    <div class="flex items-center gap-3">
        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </div>
        <div>
            <h3 class="text-base font-black leading-tight">{{ $selectedBarang->nama_barang }}</h3>
            <p class="text-blue-100 text-[10px] font-mono tracking-tighter uppercase opacity-80">{{ $selectedBarang->kode_barang }} | {{ $selectedBarang->satuan }}</p>
        </div>
    </div>
    <div class="text-right">
        <p class="text-blue-100 text-[9px] font-bold uppercase tracking-widest mb-0.5 opacity-80">Stok Saat Ini</p>
        <p class="text-2xl font-black">{{ (int)$selectedBarang->stok_sekarang }} <span class="text-[10px] font-normal opacity-70">{{ strtoupper($selectedBarang->satuan) }}</span></p>
    </div>
</div>
@endif

<!-- Tabel Laporan -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-xs text-left">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-center w-24">Tanggal</th>
                    <th class="px-4 py-3">Supplier / Pelanggan</th>
                    <th class="px-4 py-3 text-center w-24">Tipe</th>
                    <th class="px-4 py-3 text-right text-green-600 w-24">Masuk</th>
                    <th class="px-4 py-3 text-right text-red-600 w-24">Keluar</th>
                    <th class="px-4 py-3 text-right w-24">Stok Akhir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @if($logs && $logs->count() > 0)
                    @foreach($logs as $log)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <span class="text-gray-700 font-bold">{{ date('d/m/y', strtotime($log->tanggal_log)) }}</span>
                            <br>
                            <span class="text-[9px] text-gray-400 font-mono">{{ date('H:i', strtotime($log->tanggal_log)) }}</span>
                        </td>
                        <td class="px-4 py-3 text-[11px]">
                            <div class="font-black text-gray-800 uppercase tracking-tight">
                                {{ $log->entity_nama }}
                            </div>
                            <div class="text-[9px] text-gray-400 font-mono mt-0.5">
                                Ref: {{ $log->nomor_referensi ?? '-' }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $badgeClass = [
                                    'pembelian' => 'bg-green-50 text-green-600 border-green-100',
                                    'penjualan' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'opname'    => 'bg-purple-50 text-purple-600 border-purple-100',
                                    'adjustment' => 'bg-orange-50 text-orange-600 border-orange-100'
                                ][$log->jenis_pergerakan] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                            @endphp
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-tighter border {{ $badgeClass }}">
                                {{ str_replace('_', ' ', $log->jenis_pergerakan) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if($log->jumlah_pergerakan > 0)
                                <span class="font-black text-green-600 text-sm">+{{ (int)$log->jumlah_pergerakan }}</span>
                            @else
                                <span class="text-gray-200">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if($log->jumlah_pergerakan < 0)
                                <span class="font-black text-red-600 text-sm">{{ (int)abs($log->jumlah_pergerakan) }}</span>
                            @else
                                <span class="text-gray-200">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-black text-gray-900 bg-gray-50/50">
                            {{ (int)$log->stok_sesudah }}
                        </td>
                    </tr>
                    @endforeach
                @elseif($kode_barang || $keyword)
                    <tr>
                        <td colspan="6" class="px-4 py-16 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="font-bold uppercase tracking-widest text-[10px]">Tidak ada record pergerakan stok untuk barang ini</p>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="6" class="px-4 py-20 text-center">
                            <div class="max-w-xs mx-auto">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider mb-1">Mulai Pencarian</h4>
                                <p class="text-slate-400 text-[10px] leading-relaxed">Cari barang & filter tanggal untuk melihat riwayat stok.</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    @if($logs && $logs->hasPages())
    <div class="px-4 py-3 bg-slate-50 border-t border-gray-100">
        {{ $logs->links() }}
    </div>
    @endif
</div>

<script>
    const searchInput = document.getElementById('barangSearchInput');
    const searchDropdown = document.getElementById('searchDropdown');
    const kodeBarangInput = document.getElementById('kodeBarangInput');
    let searchTimeout = null;

    searchInput.addEventListener('input', function() {
        const keyword = this.value;
        clearTimeout(searchTimeout);

        if (keyword.length < 2) {
            searchDropdown.classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`{{ route('admin.barang.search') }}?keyword=${keyword}`);
                const data = await response.json();

                if (data.length > 0) {
                    searchDropdown.innerHTML = '';
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-3 hover:bg-blue-50 cursor-pointer transition-colors group flex justify-between items-center';
                        div.innerHTML = `
                            <div>
                                <div class="font-bold text-gray-900 group-hover:text-blue-600">${item.nama_barang}</div>
                                <div class="text-[10px] text-gray-400 font-mono uppercase">${item.kode_barang}</div>
                            </div>
                            <div class="text-[10px] font-black text-blue-500 uppercase tracking-tighter bg-blue-50 px-2 py-1 rounded">Stok: ${item.stok_sekarang}</div>
                        `;
                        div.onclick = () => {
                            searchInput.value = item.nama_barang;
                            kodeBarangInput.value = item.kode_barang;
                            searchDropdown.classList.add('hidden');
                            // Auto submit after selection
                            searchInput.closest('form').submit();
                        };
                        searchDropdown.appendChild(div);
                    });
                    searchDropdown.classList.remove('hidden');
                } else {
                    searchDropdown.innerHTML = '<div class="px-4 py-8 text-center text-gray-400 italic text-xs font-bold uppercase tracking-widest">Barang tidak ditemukan</div>';
                    searchDropdown.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error searching barang:', error);
            }
        }, 300);
    });

    // Close dropdown on click outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
            searchDropdown.classList.add('hidden');
        }
    });

    // Prevent direct form submit if input is empty but allow Enter to work normally
</script>
@endsection
