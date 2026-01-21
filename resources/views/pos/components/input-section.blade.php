<!-- POS Input Section Component -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
    <div class="flex items-center gap-1.5 mb-2">
        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
        </svg>
        <label class="text-xs font-semibold text-gray-700 flex gap-2">Scan Barcode / Input Kode <span class="hidden lg:flex">(Enter untuk tambah)</span></label>
    </div>
    <div class="relative">
        <input ref="barcodeInput" type="text" v-model="barcode" @keydown.enter="tambahBarang"
            @keydown.f2.prevent="focusBarcode" @keydown.f3.prevent="manualInput"
            @keydown.f8.prevent="toggleEditMode" @keydown.f9.prevent="bukaModalPembayaran"
            @keydown.esc.prevent="handleEsc"
            class="w-full px-3 py-2 pr-10 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-100 outline-none transition-all text-sm"
            placeholder="Scan barcode atau cari manual..." autofocus />
        <button @click="manualInput"
            class="absolute right-2 top-1/2 -translate-y-1/2 p-1 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </div>
</div>