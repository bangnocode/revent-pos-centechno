@extends('admin.layout.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Daftar Barang</h2>
        <p class="text-sm text-gray-500">Kelola katalog produk toko anda</p>
    </div>
    @auth
    <div class="flex items-center gap-2">
        <button onclick="openOpnameModal()" class="inline-flex items-center gap-2 px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Cek Barang
        </button>
        <form action="{{ route('admin.barang.update-stok-massal') }}" method="POST" onsubmit="return confirm('Update stok untuk semua barang yang memiliki selisih?');" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Update Stok
            </button>
        </form>
        <a href="{{ route('admin.barang.create') }}" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Barang
        </a>
    </div>
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
                    <th class="px-6 py-3 text-center whitespace-nowrap">Nilai Stok</th>
                    <th class="px-6 py-3 text-center">Supplier</th>
                    <th class="px-6 py-3 text-center whitespace-nowrap">Stok Masuk</th>
                    <th class="px-6 py-3 text-center whitespace-nowrap">Stok Keluar</th>
                    <th class="px-6 py-3 text-center whitespace-nowrap">Selisih Barang</th>
                    <th class="px-6 py-3 text-center whitespace-nowrap">Tanggal Cek Stok</th>
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
                    <td class="px-6 py-4 text-right font-medium text-gray-900 whitespace-nowrap">
                        Rp {{ number_format($item->harga_beli_terakhir * $item->stok_sekarang, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($item->nama_supplier)
                            <span class="text-sm text-gray-700 whitespace-nowrap">{{ $item->nama_supplier }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($item->tgl_stok_masuk)
                            <span class="text-sm text-gray-700 whitespace-nowrap">{{ date('d/m/Y', strtotime($item->tgl_stok_masuk)) }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($item->tgl_stok_keluar)
                            <span class="text-sm text-gray-700 whitespace-nowrap">{{ date('d/m/Y', strtotime($item->tgl_stok_keluar)) }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $item->selisih_stok > 0 ? 'bg-blue-100 text-blue-800' : ($item->selisih_stok < 0 ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $item->selisih_stok > 0 ? '+' : '' }}{{ $item->selisih_stok }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        @if($item->tanggal_cek_stok)
                            <span class="text-xs text-gray-700">{{ date('d/m/Y H:i', strtotime($item->tanggal_cek_stok)) }}</span>
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
                    <td colspan="11" class="px-6 py-12 text-center text-gray-500">
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

<!-- Opname Modal -->
<div id="opnameModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center">
        <div class="fixed inset-0 bg-gray-900/40 transition-opacity" aria-hidden="true" onclick="closeOpnameModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-4 sm:align-middle sm:max-w-lg sm:w-full">
            <!-- Header -->
            <div class="bg-slate-800 px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-yellow-500/20 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider" id="modal-title">Cek Stok Barang</h3>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest mt-0.5">Input stok fisik aktual gudang</p>
                    </div>
                </div>
                <button onclick="closeOpnameModal()" class="text-slate-400 hover:text-white transition-colors p-1.5 hover:bg-slate-700 rounded-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="bg-white px-6 py-6">
                <div class="space-y-5">
                    <!-- Kode Barang with Search Button -->
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2">Kode / Barcode Barang</label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <input type="text" id="opnameKodeBarang" 
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-100 focus:border-yellow-500 outline-none transition-all text-sm font-mono font-bold" 
                                    placeholder="Input kode atau scan barcode..."
                                    onkeypress="if(event.key === 'Enter') cekBarangInfo()">
                                <div class="absolute right-3 top-2.5 text-gray-300">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                </div>
                            </div>
                            <button onclick="openSearchModal()" 
                                class="px-4 py-2.5 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors font-bold text-xs flex items-center gap-2 border border-slate-200">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                CARI
                            </button>
                            <button onclick="cekBarangInfo()" 
                                class="px-4 py-2.5 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-all font-bold text-xs shadow-sm shadow-yellow-200">
                                CEK
                            </button>
                        </div>
                    </div>

                    <!-- Barang Info Display -->
                    <div id="opnameBarangInfo" class="hidden animate-in fade-in slide-in-from-top-2 duration-300">
                        <div class="p-4 bg-yellow-50 rounded-xl border border-yellow-100 flex items-start gap-4">
                            <div class="p-3 bg-white rounded-lg shadow-sm">
                                <svg class="w-6 h-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div id="opnameNamaBarang" class="text-sm font-extrabold text-slate-800 leading-tight"></div>
                                <div class="grid grid-cols-2 gap-x-4 gap-y-1 mt-2">
                                    <div class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Stok Sistem</div>
                                    <div class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">Harga Jual</div>
                                    <div class="text-sm font-bold text-yellow-700"><span id="opnameStokDatabaseVal">0</span> <span id="opnameSatuanLabel">PCS</span></div>
                                    <div id="opnameHargaJual" class="text-sm font-bold text-slate-700"></div>
                                </div>
                                <input type="hidden" id="opnameStokDatabase">
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="block text-xs font-black text-slate-700 uppercase mb-3 text-center tracking-widest">JUMLAH STOK FISIK AKTUAL</label>
                        <div class="flex flex-col items-center gap-3">
                            <div class="relative">
                                <input type="number" id="opnameStokGudang" min="0" 
                                    class="w-40 px-4 py-2 border-2 border-slate-200 rounded-2xl focus:border-yellow-500 focus:ring-4 focus:ring-yellow-100 outline-none transition-all text-xs font-black text-center text-slate-800" 
                                    placeholder="0">
                            </div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase italic">Pastikan hitungan fisik sudah sesuai</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 px-6 py-4 flex gap-3 sm:flex-row-reverse rounded-b-xl border-t border-slate-100">
                <button type="button" onclick="simpanOpname()" 
                    class="flex-1 sm:flex-none inline-flex justify-center items-center rounded-xl px-8 py-3 bg-yellow-600 text-xs font-black uppercase tracking-widest text-white hover:bg-yellow-700 transition-all shadow-lg shadow-yellow-200 active:transform active:scale-95">
                    Simpan Hasil
                </button>
                <button type="button" onclick="closeOpnameModal()" 
                    class="flex-1 sm:flex-none inline-flex justify-center rounded-xl border border-slate-200 px-8 py-3 bg-white text-xs font-black uppercase tracking-widest text-slate-500 hover:bg-slate-50 transition-all">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Search Barang Modal -->
<div id="searchModal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeSearchModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <div class="bg-white">
                <!-- Search Header -->
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <h2 class="font-black text-slate-800 uppercase tracking-wider">Cari Data Barang</h2>
                        </div>
                        <button onclick="closeSearchModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-200 rounded-full transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    
                    <div class="relative">
                        <input type="text" id="modalSearchInput" 
                            class="w-full px-5 py-4 pl-12 bg-white border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-50 focus:border-blue-500 outline-none transition-all text-sm font-bold shadow-sm"
                            placeholder="Ketik nama barang atau barcode...">
                    </div>
                </div>

                <!-- Results -->
                <div class="max-h-[50vh] overflow-y-auto p-4 bg-slate-50/30">
                    <div id="modalSearchResults" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Initial State -->
                        <div class="col-span-full py-12 text-center text-slate-400">
                            <svg class="w-16 h-16 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-sm font-bold uppercase tracking-widest">Masukkan kata kunci untuk mencari</p>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tekan ESC untuk menutup</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentSelectedBarang = null;

    function openOpnameModal() {
        document.getElementById('opnameModal').classList.remove('hidden');
        document.getElementById('opnameKodeBarang').focus();
    }

    function closeOpnameModal() {
        document.getElementById('opnameModal').classList.add('hidden');
        resetOpnameForm();
    }

    function openSearchModal() {
        document.getElementById('searchModal').classList.remove('hidden');
        document.getElementById('modalSearchInput').focus();
    }

    function closeSearchModal() {
        document.getElementById('searchModal').classList.add('hidden');
        document.getElementById('modalSearchInput').value = '';
        document.getElementById('modalSearchResults').innerHTML = `
            <div class="col-span-full py-12 text-center text-slate-400">
                <svg class="w-16 h-16 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-sm font-bold uppercase tracking-widest">Masukkan kata kunci untuk mencari</p>
            </div>
        `;
    }

    function resetOpnameForm() {
        document.getElementById('opnameKodeBarang').value = '';
        document.getElementById('opnameStokDatabase').value = '';
        document.getElementById('opnameStokDatabaseVal').innerText = '0';
        document.getElementById('opnameStokGudang').value = '';
        document.getElementById('opnameBarangInfo').classList.add('hidden');
        document.getElementById('opnameSatuanLabel').innerText = 'PCS';
        currentSelectedBarang = null;
    }

    // Modal Search Logic
    const modalSearchInput = document.getElementById('modalSearchInput');
    const modalSearchResults = document.getElementById('modalSearchResults');
    let searchTimeout = null;

    modalSearchInput.addEventListener('input', function() {
        const keyword = this.value;
        clearTimeout(searchTimeout);
        
        if (keyword.length < 1) {
            modalSearchResults.innerHTML = `
                <div class="col-span-full py-12 text-center text-slate-400">
                    <svg class="w-16 h-16 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-sm font-bold uppercase tracking-widest">Masukkan kata kunci untuk mencari</p>
                </div>
            `;
            return;
        }

        searchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`{{ route('admin.barang.search') }}?keyword=${keyword}`);
                const data = await response.json();
                
                if (data.length > 0) {
                    modalSearchResults.innerHTML = '';
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'bg-white p-4 rounded-xl border border-slate-100 hover:border-blue-400 hover:shadow-md transition-all cursor-pointer group flex justify-between items-center';
                        div.innerHTML = `
                            <div class="flex-1">
                                <div class="font-extrabold text-sm text-slate-800 group-hover:text-blue-600 transition-colors">${item.nama_barang}</div>
                                <div class="text-[10px] text-slate-400 font-mono mt-1">${item.kode_barang}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-[10px] font-black text-blue-600 uppercase tracking-tighter">Stok: ${Math.floor(item.stok_sekarang)}</div>
                                <div class="text-[9px] font-bold text-slate-400 mt-1 uppercase">${item.satuan}</div>
                            </div>
                        `;
                        div.onclick = () => {
                            selectBarang(item);
                            closeSearchModal();
                        };
                        modalSearchResults.appendChild(div);
                    });
                } else {
                    modalSearchResults.innerHTML = `
                        <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-xl border border-dashed border-slate-200">
                             <p class="text-sm font-bold uppercase tracking-widest italic">Barang tidak ditemukan</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error searching barang:', error);
            }
        }, 500);
    });

    function selectBarang(item) {
        currentSelectedBarang = item;
        document.getElementById('opnameKodeBarang').value = item.kode_barang;
        displayBarangInfo(item);
    }

    async function cekBarangInfo() {
        const kode = document.getElementById('opnameKodeBarang').value;
        if (!kode) return;

        try {
            const response = await fetch(`{{ route('admin.barang.search') }}?keyword=${kode}`);
            const data = await response.json();
            
            // Cari yang kodenya persis sama atau barcode persis sama
            const item = data.find(i => i.kode_barang.toLowerCase() === kode.toLowerCase() || (i.barcode && i.barcode.toLowerCase() === kode.toLowerCase()));
            
            if (item) {
                selectBarang(item);
                document.getElementById('opnameStokGudang').focus();
            } else if (data.length === 1) {
                // Jika hanya ada 1 hasil yang mirip, pilih itu saja (untuk kenyamanan)
                selectBarang(data[0]);
                document.getElementById('opnameStokGudang').focus();
            } else {
                alert('Barang dengan kode "' + kode + '" tidak ditemukan.');
                resetOpnameForm();
                document.getElementById('opnameKodeBarang').focus();
            }
        } catch (error) {
            console.error('Error checking barang info:', error);
        }
    }

    function displayBarangInfo(item) {
        document.getElementById('opnameStokDatabase').value = item.stok_sekarang;
        document.getElementById('opnameStokDatabaseVal').innerText = Math.floor(item.stok_sekarang);
        document.getElementById('opnameNamaBarang').innerText = item.nama_barang;
        document.getElementById('opnameSatuanLabel').innerText = (item.satuan || 'PCS').toUpperCase();
        document.getElementById('opnameHargaJual').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(item.harga_jual_normal);
        document.getElementById('opnameBarangInfo').classList.remove('hidden');
    }

    async function simpanOpname() {
        const kode = document.getElementById('opnameKodeBarang').value;
        const stokGudang = document.getElementById('opnameStokGudang').value;

        if (!kode || stokGudang === '') {
            alert('Pilih barang dan masukkan stok fisik aktual');
            return;
        }

        if (stokGudang < 0) {
            alert('Stok fisik tidak boleh minus');
            return;
        }

        try {
            const response = await fetch(`{{ route('admin.barang.cek-stok') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    kode_barang: kode,
                    stok_gudang: stokGudang
                })
            });

            const result = await response.json();
            if (result.success) {
                alert(result.message);
                location.reload();
            } else {
                alert('Gagal menyimpan: ' + (result.message || 'Error tidak dikenal'));
            }
        } catch (error) {
            console.error('Error saving opname:', error);
            alert('Terjadi kesalahan saat menyimpan data');
        }
    }

    // Global Key Listener for Modal closing
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('searchModal').classList.contains('hidden')) {
                closeSearchModal();
            } else if (!document.getElementById('opnameModal').classList.contains('hidden')) {
                closeOpnameModal();
            }
        }
    });

</script>
@endsection
