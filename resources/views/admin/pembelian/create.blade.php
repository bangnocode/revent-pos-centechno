@extends('admin.layout.app')

@section('content')
<div class="max-w-5xl mx-auto" x-data="kulakanHandler()">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Input Kulakan</h2>
            <p class="text-sm text-gray-500">Catat pembelian barang dari supplier</p>
        </div>
    </div>

    <form @submit.prevent="submitForm">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Transaction Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                    <h3 class="font-semibold text-gray-800 mb-4 border-b pb-2">Info Transaksi</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Faktur</label>
                            <input type="text" x-model="form.nomor_faktur" readonly
                                class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="datetime-local" x-model="form.tanggal" required
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-red-500">*</span></label>
                            <select x-model="form.supplier_id" required
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}">{{ $sup->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                            <select x-model="form.metode_pembayaran" required
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm">
                                <option value="tunai">Tunai</option>
                                <option value="hutang">Hutang</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                            <textarea x-model="form.keterangan" rows="2"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm"></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                     <h3 class="font-semibold text-gray-800 mb-4 border-b pb-2">Ringkasan</h3>
                     <div class="flex justify-between items-center mb-2">
                         <span class="text-gray-600">Total Item:</span>
                         <span class="font-medium" x-text="form.items.length"></span>
                     </div>
                     <div class="flex justify-between items-center text-lg font-bold text-gray-900 mt-4 pt-4 border-t border-dashed">
                         <span>Grand Total:</span>
                         <span x-text="formatRupiah(grandTotal)"></span>
                     </div>
                     <button type="submit" 
                        :disabled="isSubmitting || form.items.length === 0"
                        class="w-full mt-6 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors shadow-sm flex justify-center items-center gap-2">
                        <svg x-show="isSubmitting" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Transaksi'"></span>
                     </button>
                </div>
            </div>

            <!-- Right: Items -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-800 mb-4 border-b pb-2">List Barang</h3>

                <!-- Add Item Form -->
                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <div class="space-y-4">
                        <!-- Row 1: Kode Barang and Quantity -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang <span class="text-red-500">*</span></label>
                                <div class="flex gap-2">
                                    <input type="text" x-model="newItem.kode_barang" @input="validateKodeBarang"
                                        class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm"
                                        placeholder="Masukkan kode barang">
                                    <button type="button" @click="openBarangModal" 
                                        class="px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm">
                                        Cari
                                    </button>
                                    <button type="button" @click="openNewBarangModal" 
                                        class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm flex items-center gap-1 whitespace-nowrap">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Barang Baru
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                                <input type="number" x-model.number="newItem.jumlah" min="0" step="1" maxlength="6" max="100000"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm"
                                    placeholder="0">
                            </div>
                        </div>
                        <!-- Row 2: Subtotal -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal <span class="text-red-500">*</span></label>
                                <input type="text" x-model="newItem.subtotal" @input="newItem.subtotal = formatNumberRibuan($event.target.value)"
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm"
                                    placeholder="0" inputmode="numeric">
                            </div>
                        </div>

                        <!-- Row 2: Validation Message and Button -->
                        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                            <div class="flex-1">
                                <div x-show="validationMessage" class="text-sm" :class="isValidKode ? 'text-green-600' : 'text-red-600'" x-text="validationMessage"></div>
                                <div x-show="selectedBarang" class="mt-1 text-sm text-gray-600">
                                    <strong x-text="selectedBarang.nama_barang"></strong> | Stok: <span x-text="selectedBarang.stok_sekarang"></span> | Harga Jual: <span x-text="formatRupiah(selectedBarang.harga_jual_normal)"></span>
                                </div>
                            </div>
                            <div class="w-full sm:w-auto">
                                <button type="button" @click="addItem" :disabled="!isValidKode || !newItem.jumlah || !newItem.subtotal"
                                    class="w-full sm:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-medium rounded-lg transition-colors text-sm">
                                    Tambah Barang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-medium">
                            <tr>
                                <th class="px-4 py-3 rounded-l-lg">Barang</th>
                                <th class="px-4 py-3 w-24 text-center">Qty</th>
                                <th class="px-4 py-3 w-32 text-right">Harga Beli</th>
                                <th class="px-4 py-3 w-32 text-right">Harga Jual</th>
                                <th class="px-4 py-3 w-32 text-right">Subtotal</th>
                                <th class="px-4 py-3 w-10 text-center rounded-r-lg"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(item, index) in form.items" :key="index">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900" x-text="item.nama_barang"></div>
                                        <div class="text-xs text-slate-500" x-text="item.kode_barang"></div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" x-model="item.jumlah" @input="item.jumlah = formatNumberRibuan($event.target.value)"
                                            class="w-full px-2 py-1 border border-gray-200 rounded text-center focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm" inputmode="numeric">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" x-model="item.harga_beli" @input="item.harga_beli = formatNumberRibuan($event.target.value)"
                                            class="w-full px-2 py-1 border border-gray-200 rounded text-right focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm" inputmode="numeric">
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="text-gray-900" x-text="formatRupiah(item.harga_jual)"></div>
                                    </td>
                                    <td class="px-4 py-3 text-right font-medium text-gray-900" x-text="formatRupiah(unformatNumberRibuan(item.jumlah) * unformatNumberRibuan(item.harga_beli))">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" @click="removeItem(index)" class="text-red-400 hover:text-red-600">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="form.items.length === 0">
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500 text-sm">
                                    Belum ada barang dipilih. Klik tombol + Tambah Barang.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal Pencarian Barang -->
    <div x-show="showBarangModal" x-cloak 
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-3"
        @keydown.esc.window="closeBarangModal">
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
                    <button @click="closeBarangModal" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-full transition-colors font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Search Input -->
                <div class="relative">
                    <input type="text" x-model="barangSearchKeyword" @input.debounce.300ms="searchBarang" @keydown.esc="closeBarangModal"
                        class="w-full px-4 py-2.5 pl-11 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all text-sm"
                        placeholder="Cari nama barang, kode, atau barcode..." x-ref="searchInput">
                    <svg class="absolute left-4 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            
                            <!-- Badges -->
                            <div x-show="parseFloat(barang.stok_sekarang) <= 0" 
                                class="absolute top-0 right-0 bg-red-600 text-white text-[10px] px-2 py-0.5 font-bold uppercase tracking-wider transform translate-x-[20%] translate-y-[50%] rotate-45 w-[100px] text-center shadow-sm z-10">
                                Habis
                            </div>

                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 group-hover:text-blue-600 transition-colors" x-text="barang.nama_barang"></h3>
                                    <div class="flex flex-wrap gap-x-3 gap-y-1 mt-2 text-xs text-gray-500">
                                        <span class="flex items-center gap-1 font-mono">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                            </svg>
                                            <span x-text="barang.kode_barang"></span>
                                        </span>
                                        <span x-show="barang.barcode" class="flex items-center gap-1 font-mono">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                            <span x-text="barang.barcode"></span>
                                        </span>
                                    </div>
                                    <div class="mt-3 flex items-center gap-2">
                                        <span :class="parseFloat(barang.stok_sekarang) <= 0 ? 'bg-red-50 text-red-600 border-red-100' : 'bg-green-50 text-green-600 border-green-100'"
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold border">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                            Stok: <span x-text="Math.floor(barang.stok_sekarang)"></span> <span x-text="barang.satuan"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right flex flex-col items-end">
                                    <div class="text-sm font-bold text-blue-600 mb-1" x-text="formatRupiah(barang.harga_jual_normal)"></div>
                                    <button @click.stop="selectBarang(barang)"
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
                    <svg class="w-16 h-16 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <p class="text-gray-600 font-bold italic">Barang tidak ditemukan</p>
                    <p class="text-gray-400 text-sm mt-1">Gunakan kata kunci pencarian yang lain.</p>
                </div>

                <!-- Initial State -->
                <div x-show="!isSearchingBarang && barangList.length === 0 && barangSearchKeyword.length === 0" 
                    class="flex flex-col items-center justify-center py-20 bg-white rounded-xl border border-gray-100">
                    <svg class="w-16 h-16 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-500 font-medium">Mulai mencari barang...</p>
                    <p class="text-gray-400 text-sm mt-1">Ketik nama barang, kode, atau barcode di atas</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-gray-100 px-6 py-4 bg-gray-50 flex justify-between items-center transition-all">
                <p class="text-xs text-gray-500 italic" x-show="barangList.length > 0">
                    Ditemukan <span class="font-bold text-blue-600" x-text="barangList.length"></span> barang
                </p>
                <div class="flex gap-2 ml-auto">
                    <button @click="closeBarangModal" class="px-5 py-2 text-xs font-bold text-gray-600 border border-gray-200 bg-white rounded-lg hover:bg-gray-50 transition-colors">
                        TUTUP (ESC)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Barang Baru -->
    <div x-show="showNewBarangModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60] overflow-y-auto py-10">
        <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4 my-auto">
            <div class="p-4 border-b">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Barang Baru</h3>
                    <button @click="closeNewBarangModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <form @submit.prevent="saveNewBarang">
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
                            <input type="text" x-model="newBarang.kode_barang"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm"
                                placeholder="Kosongkan untuk generate otomatis">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Barcode / SKU <span class="text-red-500">*</span></label>
                            <input type="text" x-model="newBarang.barcode" required
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm"
                                placeholder="Scan barcode disini...">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" x-model="newBarang.nama_barang" required
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm"
                            placeholder="Contoh: Kopi Kapal Api 65gr">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <input type="text" x-model="newBarang.kategori"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm"
                                placeholder="Contoh: Minuman">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Satuan <span class="text-red-500">*</span></label>
                            <select x-model="newBarang.satuan_id" required
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm">
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuans as $sat)
                                <option value="{{ $sat->id }}">{{ $sat->nama_satuan }} {{ $sat->singkatan ? '('.$sat->singkatan.')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual Normal <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                                <input type="text" x-model="newBarang.harga_jual_normal" @input="newBarang.harga_jual_normal = formatNumberRibuan($event.target.value)" required
                                    class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm"
                                    placeholder="0">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stok Awal</label>
                            <input type="text" x-model="newBarang.stok_sekarang" @input="newBarang.stok_sekarang = formatNumberRibuan($event.target.value)"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-100 focus:border-blue-500 outline-none text-sm"
                                placeholder="0">
                        </div>
                    </div>
                </div>
                <div class="p-4 border-t bg-gray-50 flex justify-end gap-3 rounded-b-lg">
                    <button type="button" @click="closeNewBarangModal"
                        class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" :disabled="isSavingBarang"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white text-sm font-semibold rounded-lg shadow-sm flex items-center gap-2">
                        <svg x-show="isSavingBarang" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSavingBarang ? 'Menyimpan...' : 'Simpan Barang'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
window.unformatNumberRibuan = function(n) {
    if (!n) return "";
    return String(n).replace(/\./g, "");
};

window.formatNumberRibuan = function(n) {
    if (!n) return "";
    return String(n).replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

document.addEventListener('alpine:init', () => {
    Alpine.data('kulakanHandler', () => ({
        form: {
            nomor_faktur: '{{ $nomorFaktur }}',
            tanggal: new Date().toISOString().slice(0, 16),
            supplier_id: '',
            metode_pembayaran: '',
            keterangan: '',
            items: []
        },
        newItem: {
            kode_barang: '',
            jumlah: 0,
            subtotal: ''
        },
        selectedBarang: null,
        isValidKode: false,
        validationMessage: '',
        isSubmitting: false,
        showBarangModal: false,
        barangSearchKeyword: '',
        barangList: [],
        isSearchingBarang: false,
        selectedSearchIndex: -1,

        // New Barang Modal
        showNewBarangModal: false,
        isSavingBarang: false,
        newBarang: {
            kode_barang: '',
            barcode: '',
            nama_barang: '',
            kategori: '',
            satuan_id: '',
            harga_beli_terakhir: 0,
            harga_jual_normal: '0',
            stok_sekarang: '0'
        },

        get grandTotal() {
            const total = this.form.items.reduce((sum, item) => {
                const qty = parseFloat(unformatNumberRibuan(item.jumlah)) || 0;
                const harga = parseFloat(unformatNumberRibuan(item.harga_beli)) || 0;
                return sum + (qty * harga);
            }, 0);
            return Math.round(total);
        },

        async validateKodeBarang() {
            if (this.newItem.kode_barang.length < 2) {
                this.isValidKode = false;
                this.validationMessage = '';
                this.selectedBarang = null;
                return;
            }
            try {
                const response = await fetch(`{{ route("admin.barang.search") }}?keyword=${this.newItem.kode_barang}`);
                const data = await response.json();
                const barang = data.find(b => b.kode_barang.toLowerCase() === this.newItem.kode_barang.toLowerCase() || (b.barcode && b.barcode.toLowerCase() === this.newItem.kode_barang.toLowerCase()));
                if (barang) {
                    this.isValidKode = true;
                    this.validationMessage = 'Barang ditemukan';
                    this.selectedBarang = barang;
                } else {
                    this.isValidKode = false;
                    this.validationMessage = 'Kode barang tidak ditemukan';
                    this.selectedBarang = null;
                }
            } catch (e) {
                this.isValidKode = false;
                this.validationMessage = 'Error validasi';
                this.selectedBarang = null;
                console.error(e);
            }
        },

        addItem() {
            if (!this.isValidKode || !this.newItem.jumlah || !this.newItem.subtotal) return;
            const subtotal = unformatNumberRibuan(this.newItem.subtotal);
            const jumlah = parseFloat(unformatNumberRibuan(this.newItem.jumlah));
            if (jumlah <= 0 || subtotal <= 0) return;
            const hargaBeli = Math.round(subtotal / jumlah);
            // Check if already in cart
            const existing = this.form.items.find(i => i.kode_barang === this.selectedBarang.kode_barang);
            if (existing) {
                existing.jumlah = (parseFloat(unformatNumberRibuan(existing.jumlah)) + jumlah).toString();
                existing.jumlah = formatNumberRibuan(existing.jumlah);
            } else {
                this.form.items.push({
                    kode_barang: this.selectedBarang.kode_barang,
                    nama_barang: this.selectedBarang.nama_barang,
                    jumlah: formatNumberRibuan(jumlah),
                    harga_beli: formatNumberRibuan(hargaBeli),
                    harga_jual: Math.round(this.selectedBarang.harga_jual_normal || 0)
                });
            }
            // Reset
            this.newItem.kode_barang = '';
            this.newItem.jumlah = 0;
            this.newItem.subtotal = '';
            this.selectedBarang = null;
            this.isValidKode = false;
            this.validationMessage = '';
        },

        removeItem(index) {
            this.form.items.splice(index, 1);
        },

        async submitForm() {
            if (this.form.items.length === 0) return;
            if (!confirm('Simpan transaksi pembelian ini? Stok barang akan bertambah.')) return;

            this.isSubmitting = true;
            // Prepare data by unformatting numbers
            const preparedForm = {
                ...this.form,
                items: this.form.items.map(item => ({
                    ...item,
                    jumlah: unformatNumberRibuan(item.jumlah),
                    harga_beli: unformatNumberRibuan(item.harga_beli)
                }))
            };

            try {
                const response = await fetch('{{ route("admin.pembelian.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(preparedForm)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    alert('Berhasil! ' + result.message);
                    window.location.href = result.redirect;
                } else {
                    alert('Gagal: ' + (result.message || 'Terjadi kesalahan sistem'));
                }
            } catch (e) {
                alert('Connection Error');
                console.error(e);
            } finally {
                this.isSubmitting = false;
            }
        },

        formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number || 0);
        },

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
            this.barangSearchKeyword = '';
            this.barangList = [];
        },

        async searchBarang() {
            if (this.barangSearchKeyword.length < 1) {
                this.barangList = [];
                this.selectedSearchIndex = -1;
                return;
            }
            this.isSearchingBarang = true;
            try {
                const response = await fetch(`{{ route("admin.barang.search") }}?keyword=${this.barangSearchKeyword}`);
                this.barangList = await response.json();
                this.selectedSearchIndex = this.barangList.length > 0 ? 0 : -1;
            } catch (e) {
                this.barangList = [];
                this.selectedSearchIndex = -1;
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
            
            // Auto scroll to element
            this.$nextTick(() => {
                const el = document.getElementById('search-item-' + newIndex);
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
        },

        tambahSelectedBarang() {
            if (this.showBarangModal && this.selectedSearchIndex >= 0 && this.selectedSearchIndex < this.barangList.length) {
                this.selectBarang(this.barangList[this.selectedSearchIndex]);
            }
        },

        selectBarang(barang) {
            this.newItem.kode_barang = barang.kode_barang;
            this.selectedBarang = barang;
            this.isValidKode = true;
            this.validationMessage = 'Barang dipilih';
            this.closeBarangModal();
        },

        openNewBarangModal() {
            this.showNewBarangModal = true;
            this.newBarang = {
                kode_barang: '',
                barcode: '',
                nama_barang: '',
                kategori: '',
                satuan_id: '',
                harga_beli_terakhir: 0,
                harga_jual_normal: '0',
                stok_sekarang: '0'
            };
        },

        closeNewBarangModal() {
            this.showNewBarangModal = false;
        },

        async saveNewBarang() {
            this.isSavingBarang = true;
            
            // Prepare data
            const data = {
                ...this.newBarang,
                harga_jual_normal: unformatNumberRibuan(this.newBarang.harga_jual_normal),
                stok_sekarang: unformatNumberRibuan(this.newBarang.stok_sekarang),
                harga_beli_terakhir: 0 // Will be updated by the purchase transaction
            };

            try {
                const response = await fetch('{{ route("admin.barang.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Success!
                    this.closeNewBarangModal();
                    // Select the new barang
                    this.newItem.kode_barang = result.data.kode_barang;
                    this.validateKodeBarang();
                    alert('Barang berhasil ditambahkan');
                } else {
                    // Handle validation errors
                    let errorMsg = result.message || 'Gagal menyimpan barang';
                    if (result.errors) {
                        errorMsg += '\n' + Object.values(result.errors).flat().join('\n');
                    }
                    alert(errorMsg);
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan koneksi');
            } finally {
                this.isSavingBarang = false;
            }
        }
    }));
});
</script>
@endsection
