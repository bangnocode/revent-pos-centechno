@extends('admin.layout.app')

@section('content')
<div x-data="stokLaporanHandler()" @keydown.window.f3.prevent="openBarangModal()">
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
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Cari Barang (F3)</label>
                <div class="relative group flex gap-2">
                    <div class="relative flex-1">
                        <input type="text" id="barangSearchInput" name="keyword" x-model="keyword" placeholder="Ketik nama barang atau barcode..." 
                            class="w-full px-3 py-2 pl-9 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-xs font-medium">
                        <div class="absolute left-3 top-2 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <button type="button" @click="openBarangModal()" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-all shadow-sm">
                        CARI
                    </button>
                </div>
                <input type="hidden" name="kode_barang" x-model="kode_barang">
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
                        FILTER
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

    <!-- Modal Pencarian Barang -->
    <div x-show="showBarangModal" 
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-3"
        style="display: none;"
        @keydown.esc.window="closeBarangModal()">
        <div class="bg-white rounded-xl w-full max-w-4xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="border-b border-gray-100 px-6 py-4 bg-gray-50">
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-blue-100 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h2 class="font-bold text-gray-800">Cari Barang</h2>
                    </div>
                    <button type="button" @click="closeBarangModal()" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-full transition-colors font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Search Input -->
                <div class="relative group">
                    <input type="text" 
                        x-model="barangSearchKeyword" 
                        @input.debounce.300ms="searchBarang()"
                        x-ref="searchInput"
                        class="w-full px-4 py-3 pl-12 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm font-medium"
                        placeholder="Ketik Nama / Kode / Barcode Barang..."
                        @keydown.arrow-down.prevent="navigasiSearch(1)"
                        @keydown.arrow-up.prevent="navigasiSearch(-1)"
                        @keydown.enter.prevent="tambahSelectedBarang()">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-auto p-4 bg-gray-50">
                <!-- Loading State -->
                <div x-show="isSearchingBarang" class="flex flex-col items-center justify-center py-20">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-100 border-t-blue-600 mb-3"></div>
                    <p class="text-gray-500 font-medium">Mencari barang...</p>
                </div>

                <!-- Results -->
                <div x-show="!isSearchingBarang && barangList.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-3"
                    @keydown.window.arrow-up.prevent="navigasiSearch(-1)"
                    @keydown.window.arrow-down.prevent="navigasiSearch(1)"
                    @keydown.window.enter.prevent="tambahSelectedBarang()">
                    <template x-for="(barang, index) in barangList" :key="barang.kode_barang">
                        <div @click="selectBarang(barang)" @mouseenter="selectedSearchIndex = index"
                            :class="selectedSearchIndex === index ? 'border-blue-500 bg-blue-50 shadow-md ring-2 ring-blue-100' : 'bg-white border-gray-100'"
                            class="rounded-xl p-4 border hover:border-blue-400 hover:shadow-md transition-all cursor-pointer group relative overflow-hidden"
                            :id="'search-item-' + index">
                            
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 group-hover:text-blue-600 transition-colors" x-text="barang.nama_barang"></h3>
                                    <div class="flex flex-wrap gap-x-3 gap-y-1 mt-2 text-xs text-gray-500">
                                        <span class="flex items-center gap-1 font-mono" x-text="barang.kode_barang"></span>
                                        <span x-show="barang.barcode" class="flex items-center gap-1 font-mono">| <span x-text="barang.barcode"></span></span>
                                    </div>
                                    <div class="mt-3 flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold border bg-green-50 text-green-600 border-green-100">
                                            Stok: <span x-text="Math.floor(barang.stok_sekarang)"></span> <span x-text="barang.satuan"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right flex flex-col items-end">
                                    <button type="button" @click.stop="selectBarang(barang)"
                                        class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-blue-100">
                                        PILIH
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Empty State -->
                <div x-show="!isSearchingBarang && barangList.length === 0 && barangSearchKeyword.length > 0" 
                    class="flex flex-col items-center justify-center py-20 bg-white rounded-xl border border-gray-100">
                    <p class="text-gray-600 font-bold italic">Barang tidak ditemukan</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-gray-100 px-6 py-4 bg-gray-50 flex justify-end items-center">
                <button type="button" @click="closeBarangModal()" class="px-5 py-2 text-xs font-bold text-gray-600 border border-gray-200 bg-white rounded-lg hover:bg-gray-50 transition-colors">
                    TUTUP (ESC)
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('stokLaporanHandler', () => ({
        keyword: '{{ $keyword }}',
        kode_barang: '{{ $kode_barang }}',
        showBarangModal: false,
        barangSearchKeyword: '',
        barangList: [],
        isSearchingBarang: false,
        selectedSearchIndex: -1,

        openBarangModal() {
            this.showBarangModal = true;
            this.barangSearchKeyword = '';
            this.barangList = [];
            this.$nextTick(() => {
                this.$refs.searchInput.focus();
            });
        },

        closeBarangModal() {
            this.showBarangModal = false;
        },

        async searchBarang() {
            if (this.barangSearchKeyword.length < 2) {
                this.barangList = [];
                return;
            }
            this.isSearchingBarang = true;
            try {
                const response = await fetch(`{{ route("admin.barang.search") }}?keyword=${this.barangSearchKeyword}`);
                this.barangList = await response.json();
                this.selectedSearchIndex = this.barangList.length > 0 ? 0 : -1;
            } catch (e) {
                console.error(e);
            } finally {
                this.isSearchingBarang = false;
            }
        },

        navigasiSearch(direction) {
            if (this.barangList.length === 0) return;
            let newIndex = this.selectedSearchIndex + direction;
            if (newIndex < 0) newIndex = this.barangList.length - 1;
            if (newIndex >= this.barangList.length) newIndex = 0;
            this.selectedSearchIndex = newIndex;
            this.$nextTick(() => {
                const el = document.getElementById('search-item-' + newIndex);
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
        },

        tambahSelectedBarang() {
            if (this.selectedSearchIndex >= 0 && this.selectedSearchIndex < this.barangList.length) {
                this.selectBarang(this.barangList[this.selectedSearchIndex]);
            }
        },

        selectBarang(barang) {
            this.keyword = barang.nama_barang;
            this.kode_barang = barang.kode_barang;
            this.closeBarangModal();
            // Auto submit
            this.$nextTick(() => {
                document.getElementById('barangSearchInput').closest('form').submit();
            });
        }
    }));
});
</script>
@endsection
