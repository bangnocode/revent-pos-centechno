<!-- POS Summary Component -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
    <div class="flex items-center gap-1.5 mb-3">
        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
        <h2 class="font-bold text-gray-800 text-sm">Ringkasan</h2>
    </div>

    <div class="space-y-2">
        <!-- Subtotal -->
        <div class="flex justify-between py-1.5">
            <span class="text-xs text-gray-600">Subtotal</span>
            <span class="text-sm font-semibold text-gray-900">Rp @{{ formatRupiah(subtotal) }}</span>
        </div>

        <!-- Total Item -->
        <div class="flex justify-between py-1.5">
            <span class="text-xs text-gray-600">Total Item</span>
            <span class="text-sm font-semibold text-gray-900">@{{ Math.floor(totalQty) }} item</span>
        </div>

        <!-- Diskon Item (jika ada) -->
        <div v-if="subtotalSetelahDiskonItem < subtotal"
            class="flex justify-between py-1.5 text-red-600">
            <span class="text-xs">Diskon Item</span>
            <span class="text-sm font-semibold">- Rp @{{ formatRupiah(subtotal - subtotalSetelahDiskonItem) }}</span>
        </div>

        <!-- Diskon Transaksi -->
        <div v-if="diskonTransaksi > 0" class="flex justify-between py-1.5 text-red-600">
            <span class="text-xs">Diskon Transaksi</span>
            <span class="text-sm font-semibold">- Rp @{{ formatRupiah(diskonTransaksi) }}</span>
        </div>

        <!-- Total -->
        <div class="border-t border-gray-200 pt-2">
            <div class="flex justify-between items-center bg-blue-50 p-2 rounded">
                <span class="font-bold text-gray-800 text-sm">TOTAL</span>
                <span class="text-lg font-bold text-blue-600">Rp @{{ formatRupiah(total) }}</span>
            </div>
        </div>
    </div>

    <button @click="bukaModalPembayaran" :disabled="cart.length === 0 || isProcessing"
        :class="{
            'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 shadow': cart
                .length > 0 && !isProcessing,
            'bg-gray-300 cursor-not-allowed': cart.length === 0 || isProcessing
        }"
        class="w-full mt-4 px-3 py-2.5 text-white font-bold rounded text-sm transition-all flex items-center justify-center gap-1.5">
        <template v-if="!isProcessing">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="text-xs flex gap-1">PEMBAYARAN <span class="hidden lg:flex">(F9)</span></span>
        </template>
        <div v-else class="flex items-center gap-1.5">
            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                    stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="text-xs">Memproses...</span>
        </div>
    </button>

    <!-- Info Transaksi -->
    <div
        class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg shadow-sm border border-gray-100 p-4">
        <div class="flex items-center gap-1.5 mb-3">
            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h2 class="font-bold text-gray-800 text-sm">Info Transaksi</h2>
        </div>

        <div class="space-y-1.5">
            <div class="flex justify-between py-1 text-xs">
                <span class="text-gray-600">Kasir</span>
                <span class="font-semibold text-gray-900">Admin</span>
            </div>
            <div class="flex justify-between py-1 text-xs">
                <span class="text-gray-600">Tanggal</span>
                <span class="font-semibold text-gray-900">@{{ currentDate }}</span>
            </div>
            <div class="flex justify-between py-1 text-xs">
                <span class="text-gray-600">Waktu</span>
                <span class="font-semibold text-gray-900">@{{ currentTime }}</span>
            </div>

            <div v-if="lastTransaction" class="border-t border-gray-300 pt-2 mt-2">
                <div class="flex justify-between py-1 text-xs">
                    <span class="text-gray-600">Transaksi Terakhir</span>
                    <span class="font-semibold text-blue-600">@{{ lastTransaction }}</span>
                </div>
            </div>
        </div>
    </div>
</div>