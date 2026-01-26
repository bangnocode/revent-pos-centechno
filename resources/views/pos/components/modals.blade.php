<!-- POS Modals Component -->

<!-- Modal Edit QTY -->
<div v-if="editQtyMode" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-3"
    @keydown.enter.prevent="applyEditQty" @keydown.esc.prevent="batalEditQty">
    <div class="bg-white rounded-lg w-full max-w-md shadow-lg">
        <!-- Header -->
        <div class="border-b border-gray-200 px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <h3 class="font-semibold text-gray-800 text-sm">Edit Jumlah Barang</h3>
            </div>
            <button @click="batalEditQty" class="text-gray-400 hover:text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="p-4">
            <!-- Info Barang -->
            <div class="mb-4 p-2.5 bg-gray-50 rounded border border-gray-200 flex justify-between items-start">
                <div>
                    <div class="text-xs text-gray-500 mb-0.5">Barang</div>
                    <div class="font-semibold text-gray-900 text-sm">@{{ selectedItem?.nama_barang }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">@{{ selectedItem?.kode_barang }}</div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-gray-500 mb-0.5">Stok Tersedia</div>
                    <div class="font-bold text-blue-600 text-sm">@{{ Math.floor(selectedItem?.stok_sekarang) }} @{{ selectedItem?.satuan }}</div>
                </div>
            </div>

            <!-- Input Quantity Section -->
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-1.5">Jumlah Baru</label>
                <div class="flex items-center gap-1.5">
                    <button @click="tempQty > 1 && tempQty--"
                        class="w-8 h-8 bg-red-100 text-red-600 rounded hover:bg-red-200 font-semibold transition-colors">
                        −
                    </button>
                    <input v-model.number="tempQty" @keydown.enter="tempQty <= selectedItem?.stok_sekarang && applyEditQty()" @keydown.esc="batalEditQty"
                        type="number" min="1"
                        :class="{'border-red-500 focus:ring-red-500': tempQty > selectedItem?.stok_sekarang}"
                        class="flex-1 px-3 py-1.5 border border-gray-300 rounded text-center text-base font-semibold focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none"
                        placeholder="Enter untuk simpan, ESC untuk batal" ref="qtyModalInput" autofocus>
                    <button @click="tempQty++"
                        class="w-8 h-8 bg-green-100 text-green-600 rounded hover:bg-green-200 font-semibold transition-colors">
                        +
                    </button>
                </div>

                <!-- Teks Error -->
                <div v-if="tempQty > selectedItem?.stok_sekarang" class="mt-2 text-red-600 text-[11px] font-bold animate-pulse flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Jumlah melebihi stok yang tersedia!
                </div>

                <p class="text-xs text-gray-500 mt-1.5 hidden lg:flex gap-1">
                    <kbd class="px-1 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs">Enter</kbd>
                    Simpan •
                    <kbd class="px-1 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs">Esc</kbd>
                    Batal
                </p>
            </div>

            <!-- Actions -->
            <div class="flex gap-1.5">
                <button @click="batalEditQty"
                    class="flex-1 flex justify-center px-3 py-1.5 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 text-xs font-medium transition-colors">
                    Batal <span class="hidden lg:flex">(ESC)</span>
                </button>
                <button @click="applyEditQty"
                    :disabled="tempQty > selectedItem?.stok_sekarang || tempQty <= 0"
                    :class="{'opacity-50 cursor-not-allowed': tempQty > selectedItem?.stok_sekarang || tempQty <= 0}"
                    class="flex-1 flex justify-center px-3 py-1.5 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs font-medium transition-colors">
                    Simpan <span class="hidden lg:flex">(Enter)</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pembayaran -->
<div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-3">
    <div class="bg-white rounded-lg w-full max-w-md shadow-lg">
        <!-- Header -->
        <div class="border-b border-gray-200 px-4 py-3">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h2 class="font-semibold text-gray-800 text-sm">Pembayaran</h2>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4 space-y-3">
            <!-- Nama Pembeli -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Nama Pembeli</label>
                <select v-model="pembayaran.nama_pelanggan"
                    class="w-full px-2.5 py-1.5 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm"
                    :disabled="isProcessing">
                    <option value="Pelanggan Umum">Pelanggan Umum</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->nama_supplier }}">{{ $sup->nama_supplier }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Metode Pembayaran -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                <select v-model="pembayaran.metode_pembayaran"
                    class="w-full px-2.5 py-1.5 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm"
                    :disabled="isProcessing">
                    <option value="tunai">Tunai</option>
                    <option value="debit">Debit Card</option>
                    <option value="kredit">Kredit Card</option>
                    <option value="transfer">Transfer</option>
                    <option value="qris">QRIS</option>
                    <option value="hutang">Hutang / Kredit</option>
                </select>
            </div>

            <!-- Diskon Transaksi -->
            <div class="bg-yellow-50 p-3 rounded border border-yellow-200">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-xs font-medium text-gray-700 font-bold uppercase">Diskon Transaksi (Global)</label>
                    <button @click="toggleDiskonMode" type="button"
                        class="text-xs px-2 py-1 rounded border @{{ diskonMode === 'nominal' ? 'bg-blue-100 border-blue-300 text-blue-700' : 'bg-green-100 border-green-300 text-green-700' }}">
                        @{{ diskonMode === 'nominal' ? 'Rp' : '%' }}
                    </button>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500">@{{ diskonMode === 'nominal' ? 'Rp' : '%' }}</span>
                    </div>
                    <input v-model="diskonInputFormatted" type="text"
                        class="w-full pl-10 pr-3 py-1.5 border border-gray-300 rounded text-right"
                        placeholder="0" :disabled="isProcessing" maxlength="15">
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    @{{ diskonMode === 'nominal' ? 'Maks: Rp ' + formatRupiah(subtotalSetelahDiskonItem) : 'Maks: 100%' }}
                </p>
            </div>

            <!-- Total Transaksi -->
            <div class="bg-blue-50 p-3 rounded border border-blue-200">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-medium text-gray-700">Total Transaksi</span>
                    <span class="text-xl font-bold text-blue-600">Rp @{{ formatRupiah(total) }}</span>
                </div>
            </div>

            <!-- Uang Dibayar Section -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1 flex gap-1">Uang Dibayar <span class="hidden lg:flex">(Enter untuk
                    proses)</span></label>
                <input v-model="uangDibayarFormatted"
                    @keydown.enter.prevent="!isProcessing && prosesPembayaran()" type="text" maxlength="15"
                    class="w-full px-3 py-2 border border-gray-300 rounded text-right text-lg font-semibold focus:border-blue-500 focus:ring-1 focus:ring-blue-100 outline-none"
                    placeholder="0" :disabled="isProcessing" ref="uangDibayarInput" inputmode="numeric" />
                <p class="text-xs text-gray-500 mt-0.5 hidden lg:flex gap-1 items-center">
                    <kbd class="px-1 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs">Enter</kbd>
                    Proses pembayaran •
                    <kbd class="px-1 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs">ESC</kbd>
                    Batal
                </p>
            </div>

            <!-- Kembalian -->
            <div v-if="pembayaran.uang_dibayar > 0"
                :class="kembalian >= 0 ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'"
                class="p-3 rounded border">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-medium text-gray-700">
                        @{{ kembalian >= 0 ? 'Kembalian' : 'Kurang Bayar (Hutang)' }}
                    </span>
                    <span class="text-lg font-bold"
                        :class="kembalian >= 0 ? 'text-green-600' : 'text-red-600'">
                        Rp @{{ formatRupiah(Math.abs(kembalian)) }}
                    </span>
                </div>
                <p v-if="kembalian < 0 && pembayaran.metode_pembayaran !== 'hutang'" class="text-xs text-red-600 mt-0.5">Uang kurang (Pilih metode Hutang jika ingin DP)</p>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="border-t border-gray-200 p-3">
            <div class="flex gap-2">
                <button @click="tutupModal" :disabled="isProcessing"
                    class="flex-1 flex justify-center gap-1 px-3 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 text-xs font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Batal <span class="hidden lg:flex">(ESC)</span>
                </button>
                @auth
                <button @click="prosesPembayaran"
                    :disabled="(pembayaran.uang_dibayar < total && pembayaran.metode_pembayaran !== 'hutang') || isProcessing"
                    :class="{
                        'bg-green-600 hover:bg-green-700': (pembayaran.uang_dibayar >= total || pembayaran.metode_pembayaran === 'hutang') && !isProcessing,
                        'bg-gray-400 cursor-not-allowed': (pembayaran.uang_dibayar < total && pembayaran.metode_pembayaran !== 'hutang') || isProcessing
                    }"
                    class="flex-1 px-3 py-2 text-white rounded text-xs font-medium transition-colors flex items-center justify-center gap-1.5">
                    <span v-if="isProcessing" class="flex items-center gap-1.5">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Memproses...
                    </span>
                    <span v-else class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Proses <span class="hidden lg:flex">(Enter)</span>
                    </span>
                </button>
                @else
                <button disabled
                    class="flex-1 px-3 py-2 bg-gray-400 cursor-not-allowed text-white rounded text-xs font-medium transition-colors flex items-center justify-center gap-1.5">
                    Login untuk Proses
                </button>
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Modal Pencarian Barang -->
<div v-if="showModalCari"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-3">
    <div class="bg-white rounded-lg w-full max-w-4xl max-h-[85vh] flex flex-col shadow-lg">
        <!-- Header -->
        <div class="border-b border-gray-200 px-4 py-3">
            <div class="flex justify-between items-center mb-2">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h2 class="font-semibold text-gray-800 text-sm">Cari Barang</h2>
                </div>
                <button @click="tutupModalCari" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Search Input -->
            <div class="relative">
                <input v-model="keywordCari" @keydown.enter="cariBarangManual" @keydown.esc="tutupModalCari"
                    type="text"
                    class="w-full px-3 py-2 pl-8 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-100 outline-none text-sm"
                    placeholder="Cari nama barang, kode, atau barcode..." ref="searchInput" autofocus>
                <svg class="absolute left-2.5 top-2.5 w-4 h-4 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 overflow-auto p-3">
            <!-- Loading State -->
            <div v-if="isLoadingCari" class="flex flex-col items-center justify-center py-10">
                <div class="animate-spin rounded-full h-8 w-8 border-3 border-blue-200 border-t-blue-600">
                </div>
                <p class="mt-2 text-gray-600 text-sm">Mencari barang...</p>
            </div>

            <!-- Results -->
            <div v-else-if="hasilPencarian.length > 0" class="space-y-1.5" tabindex="0"
                @keydown.enter.prevent="tambahSelectedBarang" @keydown.arrow-up.prevent="navigasiSearch(-1)"
                @keydown.arrow-down.prevent="navigasiSearch(1)">
                <div v-for="(barang, index) in hasilPencarian" :key="barang.kode_barang"
                    :data-search-index="index"
                    :class="{
                        'border-blue-500 bg-blue-50 shadow-sm': selectedSearchIndex === index && parseFloat(barang.stok_sekarang) > 0,
                        'border-gray-200 hover:bg-white': selectedSearchIndex !== index && parseFloat(barang.stok_sekarang) > 0,
                        'opacity-75 bg-red-50 border-red-200': parseFloat(barang.stok_sekarang) <= 0,
                        'border-red-500 bg-red-100': selectedSearchIndex === index && parseFloat(barang.stok_sekarang) <= 0
                    }"
                    class="rounded-lg p-3 border cursor-pointer transition-all relative overflow-hidden"
                    @click="tambahBarangDariPencarian(barang)" @mouseenter="selectedSearchIndex = index">
                    <!-- Out of Stock Overlay Text -->
                    <div v-if="parseFloat(barang.stok_sekarang) <= 0" class="absolute top-0 right-0 bg-red-600 text-white text-[10px] px-2 py-0.5 font-bold uppercase tracking-wider transform translate-x-[20%] translate-y-[50%] rotate-45 w-[100px] text-center shadow-sm z-20">
                        Habis
                    </div>

                    <div class="flex justify-between items-start relative z-10">
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900 text-sm">@{{ barang.nama_barang }}</h3>
                            <div class="flex flex-wrap gap-x-3 gap-y-1 mt-1 text-xs text-gray-600">
                                <span class="flex items-center gap-1 font-mono">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                    @{{ barang.kode_barang }}
                                </span>
                                <span v-if="barang.barcode" class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                    @{{ barang.barcode }}
                                </span>
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                <span
                                    :class="parseFloat(barang.stok_sekarang) <= 0 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'"
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold border border-current border-opacity-20">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Stok: @{{ Math.floor(barang.stok_sekarang) || 0 }} @{{ barang.satuan }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right ml-3 flex flex-col items-end">
                            <div class="text-lg font-black text-blue-700">
                                Rp @{{ formatRupiah(barang.harga_jual_normal) }}
                            </div>
                            <button
                                :disabled="parseFloat(barang.stok_sekarang) <= 0"
                                :class="parseFloat(barang.stok_sekarang) <= 0 ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm'"
                                class="mt-1.5 px-4 py-1.5 text-xs font-bold rounded-lg transition-all flex items-center gap-1">
                                <svg v-if="parseFloat(barang.stok_sekarang) > 0" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                @{{ parseFloat(barang.stok_sekarang) <= 0 ? 'STOK HABIS' : 'TAMBAH' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else-if="keywordCari && !isLoadingCari"
                class="flex flex-col items-center justify-center py-10">
                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-600 text-sm font-medium">Tidak ada hasil untuk "@{{ keywordCari }}"
                </p>
                <p class="text-gray-400 text-xs mt-0.5">Coba kata kunci lain</p>
            </div>

            <!-- Initial State -->
            <div v-else class="flex flex-col items-center justify-center py-10">
                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p class="text-gray-500 text-sm font-medium">Mulai mencari barang</p>
                <p class="text-gray-400 text-xs mt-0.5">Masukkan nama, kode, atau barcode</p>
            </div>
        </div>

        <!-- Footer Modal Pencarian -->
        <div class="border-t border-gray-200 px-4 py-2.5 bg-gray-50">
            <div class="flex justify-between items-center">
                <span class="text-xs text-gray-600">
                    <span class="font-semibold">@{{ hasilPencarian.length }}</span> barang ditemukan
                </span>
                <div class="flex items-center gap-3">
                    <div class="text-xs text-gray-500 hidden lg:flex items-center gap-1">
                        <kbd class="px-1 py-0.5 bg-white border border-gray-300 rounded text-xs">↑↓</kbd> Pilih
                        •
                        <kbd class="px-1 py-0.5 bg-white border border-gray-300 rounded text-xs">Enter</kbd>
                        Tambah •
                        <kbd class="px-1 py-0.5 bg-white border border-gray-300 rounded text-xs">Esc</kbd>
                        Tutup
                    </div>
                    <button @click="tutupModalCari"
                        class="px-3 py-1 border border-gray-300 text-gray-700 rounded hover:bg-gray-100 text-xs font-medium transition-colors flex gap-1">
                        Tutup <span class="hidden lg:flex">(ESC)</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Laporan Kasir -->
<div v-if="showModalLaporan" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-slate-900 bg-opacity-70 backdrop-blur-sm" @click="tutupModalLaporan"></div>

    <!-- Modal Content -->
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl z-10 flex flex-col overflow-hidden animate-fade-in-up">
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center justify-between border-b border-slate-700">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-500 rounded-lg shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white tracking-tight">Laporan Kasir</h2>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Rekapitulasi Transaksi Anda</p>
                </div>
            </div>
            <button @click="tutupModalLaporan" class="p-2 text-slate-400 hover:text-white hover:bg-slate-700 rounded-full transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto max-h-[70vh] bg-slate-50">
            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Tanggal Mulai</label>
                        <input type="date" v-model="laporanFilters.start_date" @change="updateLaporan(true)"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Tanggal Akhir</label>
                        <input type="date" v-model="laporanFilters.end_date" @change="updateLaporan(true)"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5 ml-1">Cari No. Faktur</label>
                        <div class="relative">
                            <input type="text" v-model="laporanFilters.search" @input.debounce.500ms="updateLaporan(true)" 
                                placeholder="Masukkan nomor faktur..."
                                class="w-full px-3 py-2 pl-10 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                            <svg class="absolute left-3 top-2.5 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="isLoadingLaporan" class="flex flex-col items-center justify-center py-20">
                <svg class="animate-spin h-10 w-10 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-slate-500 font-medium">Memuat data laporan...</p>
            </div>

            <div v-else>
                <!-- Summary Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Uang Diterima</p>
                        <div class="text-xl font-black text-slate-800">Rp @{{ formatRupiah(laporanData.summary.total_semua) }}</div>
                    </div>
                    <div v-for="(total, metode) in laporanData.summary.per_metode" :key="metode" 
                        class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 border-l-4"
                        :class="metode === 'tunai' ? 'border-l-green-500' : (metode === 'transfer' ? 'border-l-blue-500' : 'border-l-amber-500')">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">@{{ metode === 'hutang' ? 'Total Sisa Hutang' : 'Total ' + metode }}</p>
                        <div class="text-xl font-black text-slate-800">Rp @{{ formatRupiah(total) }}</div>
                    </div>
                    <!-- DP Hutang Card -->
                    <div v-if="laporanData.summary.kontan_hutang > 0" 
                        class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 border-l-4 border-l-red-500">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total DP Hutang</p>
                        <div class="text-xl font-black text-slate-800">Rp @{{ formatRupiah(laporanData.summary.kontan_hutang) }}</div>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
                      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Transaksi</p>
                      <div class="text-xl font-black text-slate-800">@{{ laporanData.summary.jumlah_transaksi }}</div>
                  </div>
                </div>

                <!-- Transaction Table -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-800 text-white font-semibold uppercase tracking-wider text-[10px]">
                            <tr>
                                <th class="px-6 py-4">Faktur & Waktu</th>
                                <th class="px-6 py-4">Kasir</th>
                                <th class="px-6 py-4">Pelanggan</th>
                                <th class="px-6 py-4">Metode</th>
                                <th class="px-6 py-4 text-right">Total Belanja</th>
                                <th class="px-6 py-4 text-right">Sisa Hutang</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="tr in laporanData.transaksi.data" :key="tr.nomor_faktur" class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">@{{ tr.nomor_faktur }}</div>
                                    <div class="text-[10px] text-slate-500 font-medium">@{{ tr.tanggal_transaksi }}</div>
                                </td>
                                <td class="px-6 py-4">
                                  <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold">@{{ tr.id_operator }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-700">@{{ tr.nama_pelanggan || 'Pelanggan Umum' }}</div>
                                    <div class="text-[10px] text-slate-400">@{{ tr.jumlah_item }} Barang</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest"
                                        :class="tr.metode_pembayaran === 'tunai' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'">
                                        @{{ tr.metode_pembayaran }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="font-black text-slate-900">Rp @{{ formatRupiah(tr.total_transaksi) }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="font-black text-red-600">Rp @{{ tr.kembalian < 0 ? formatRupiah(Math.abs(tr.kembalian)) : 0 }}</div>
                                </td>
                            </tr>
                            <tr v-if="laporanData.transaksi.data.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V5a2 2 0 00-2-2H6a2 2 0 00-2 2v1m16 0l-1-1m-1 1l-1-1" />
                                    </svg>
                                    <p class="font-bold italic">Belum ada data transaksi untuk filter ini.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                <div v-if="laporanData.transaksi.last_page > 1" class="mt-4 flex items-center justify-between bg-white px-4 py-3 rounded-xl border border-slate-200">
                    <div class="flex flex-1 justify-between sm:hidden">
                        <button @click="setPageLaporan(laporanData.transaksi.current_page - 1)"
                            :disabled="laporanFilters.page <= 1"
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50">
                            Sebelumnya
                        </button>
                        <button @click="setPageLaporan(laporanData.transaksi.current_page + 1)"
                            :disabled="laporanFilters.page >= laporanData.transaksi.last_page"
                            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50">
                            Selanjutnya
                        </button>
                    </div>
                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs text-slate-500">
                                Menampilkan
                                <span class="font-bold text-slate-800">@{{ laporanData.transaksi.from }}</span>
                                sampai
                                <span class="font-bold text-slate-800">@{{ laporanData.transaksi.to }}</span>
                                dari
                                <span class="font-bold text-slate-800">@{{ laporanData.transaksi.total }}</span>
                                hasil
                            </p>
                        </div>
                        <div>
                            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                <button @click="setPageLaporan(laporanData.transaksi.current_page - 1)"
                                    :disabled="laporanFilters.page <= 1"
                                    class="relative inline-flex items-center rounded-l-md px-2 py-2 text-slate-400 ring-1 ring-inset ring-slate-300 hover:bg-slate-50 focus:z-20 focus:outline-offset-0 disabled:opacity-40">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01.02 1.06L9.41 10l3.4 3.71a.75.75 0 11-1.14 1.02l-3.75-4.09a.75.75 0 010-1.02l3.75-4.09a.75.75 0 011.06-.02z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-slate-700 ring-1 ring-inset ring-slate-300 focus:outline-offset-0 bg-slate-50">
                                    Halaman @{{ laporanData.transaksi.current_page }} dari @{{ laporanData.transaksi.last_page }}
                                </span>

                                <button @click="setPageLaporan(laporanData.transaksi.current_page + 1)"
                                    :disabled="laporanFilters.page >= laporanData.transaksi.last_page"
                                    class="relative inline-flex items-center rounded-r-md px-2 py-2 text-slate-400 ring-1 ring-inset ring-slate-300 hover:bg-slate-50 focus:z-20 focus:outline-offset-0 disabled:opacity-40">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L10.59 10 7.19 6.29a.75.75 0 111.14-1.02l3.75 4.09a.75.75 0 010 1.02l-3.75 4.09a.75.75 0 01-1.06.02z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-slate-100 px-6 py-4 border-t border-slate-200 flex justify-end items-center gap-4">
            <p class="text-[11px] text-slate-500 font-medium italic">Total @{{ laporanData.transaksi.total }} transaksi ditemukan</p>
            
            <!-- Button Print Laporan Hari Ini -->
            <button v-if="laporanFilters.start_date === today && laporanFilters.end_date === today"
                @click="printLaporanKasir" 
                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-all shadow-lg hover:shadow-blue-600/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2-2H7a2 2 0 00-2 2v4m14 0h-2" />
                </svg>
                PRINT LAPORAN HARI INI
            </button>

            <button @click="tutupModalLaporan" class="px-6 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition-all shadow-lg hover:shadow-slate-800/20">
                TUTUP (ESC)
            </button>
        </div>
    </div>
</div>