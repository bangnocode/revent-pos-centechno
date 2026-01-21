<!-- POS Cart Component -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h2 class="font-bold text-gray-800 text-sm">Keranjang Belanja</h2>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">
                    @{{ cart.length }} item • @{{ Math.floor(totalQty) }} pcs
                </span>
                <button @click="toggleEditMode"
                    :class="editMode ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-700 hover:bg-gray-800'"
                    class="px-3 py-1 text-white text-xs font-medium rounded transition-all shadow-sm flex gap-1">
                    @{{ editMode ? 'Selesai' : 'Edit' }} <span class="lg:flex hidden">(F8)</span>
                </button>
            </div>
        </div>

        <!-- Edit Mode Indicator -->
        <div v-if="editMode" class="mt-2 p-2 bg-amber-50 border-l-3 border-amber-400 rounded hidden lg:flex">
            <div class="flex items-center gap-1.5 mb-1.5">
                <svg class="w-3.5 h-3.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <span class="font-semibold text-amber-800 text-xs">Mode Edit Aktif - Gunakan shortcut:</span>
            </div>
            <div class="flex flex-wrap gap-1 text-xs text-amber-700">
                <span class="bg-white px-1.5 py-0.5 rounded text-xs">
                    <kbd class="px-0.5 text-xs">↑↓</kbd> Navigasi
                </span>
                <span class="bg-white px-1.5 py-0.5 rounded text-xs">
                    <kbd class="px-0.5 text-xs">+</kbd>/<kbd class="px-0.5 text-xs">-</kbd> Qty
                </span>
                <span class="bg-white px-1.5 py-0.5 rounded text-xs">
                    <kbd class="px-0.5 text-xs">Enter</kbd> Edit
                </span>
                <span class="bg-white px-1.5 py-0.5 rounded text-xs">
                    <kbd class="px-0.5 text-xs">Del</kbd> Hapus
                </span>
                <span class="bg-white px-1.5 py-0.5 rounded text-xs">
                    <kbd class="px-0.5 text-xs">ESC</kbd> Keluar
                </span>
            </div>
        </div>
    </div>

    <!-- Cart Items -->
    <div class="overflow-auto max-h-[400px]">
        <table class="min-w-full">
            <thead class="bg-gray-50 sticky top-0">
                <tr class="border-b border-gray-200">
                    <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 uppercase">
                        No</th>
                    <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 uppercase">
                        Barang</th>
                    <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 uppercase">
                        Harga</th>
                    <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 uppercase">
                        Qty</th>
                    <th v-if="editMode"
                        class="px-3 py-2 text-left text-xs font-bold text-gray-600 uppercase">
                        Diskon Item
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 uppercase">
                        Subtotal</th>
                    <th v-if="editMode"
                        class="px-3 py-2 text-left text-xs font-bold text-gray-600 uppercase">
                        Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr v-for="(item, index) in cart" :key="`${item.kode_barang}-${index}`"
                    :class="{
                        'bg-blue-50 border-l-3 border-blue-500': editMode && editSelectedIndex === index,
                        'hover:bg-gray-50': true
                    }"
                    @click="editMode && (editSelectedIndex = index)"
                    class="transition-colors cursor-pointer">
                    <td class="px-3 py-2 text-xs font-medium text-gray-900">@{{ index + 1 }}
                    </td>
                    <td class="px-3 py-2">
                        <div class="text-xs font-semibold text-gray-900">@{{ item.nama_barang }}
                        </div>
                        <div class="text-xs text-gray-500 font-mono">@{{ item.kode_barang }}</div>
                    </td>
                    <td class="px-3 py-2 text-xs text-gray-700 font-medium">
                        Rp @{{ formatRupiah(item.harga_satuan) }}
                    </td>
                    <td class="px-3 py-2">
                        <div v-if="editMode && editSelectedIndex === index"
                            class="flex items-center gap-1">
                            <button @click.stop="kurangiQty(index)"
                                class="w-6 h-6 bg-red-100 text-red-600 rounded text-xs hover:bg-red-200 font-bold transition-all">
                                −
                            </button>
                            <span
                                class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded min-w-[50px] text-center">
                                @{{ Math.floor(item.jumlah) }} @{{ item.satuan }}
                            </span>
                            <button @click.stop="tambahQty(index)"
                                class="w-6 h-6 bg-green-100 text-green-600 rounded text-xs hover:bg-green-200 font-bold transition-all">
                                +
                            </button>
                            <button @click.stop="startEditQty(index)"
                                class="px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 font-medium transition-all">
                                Edit
                            </button>
                        </div>
                        <div v-else class="text-xs font-medium text-gray-900">
                            @{{ Math.floor(item.jumlah) }} @{{ item.satuan }}
                        </div>
                    </td>
                    <td v-if="editMode" class="px-3 py-2">
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-xs">Rp</span>
                            </div>
                            <input :value="formatRupiah(item.diskon_item)" type="text"
                                @input="setDiskonItem(index, $event.target.value)"
                                class="w-28 pl-7 pr-2 py-1 border border-gray-300 rounded text-xs text-right font-medium focus:border-blue-500 focus:ring-1 focus:ring-blue-100 outline-none">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-0.5 whitespace-nowrap">
                            Maks: Rp @{{ formatRupiah(item.harga_satuan * item.jumlah) }}
                        </p>
                    </td>
                    <td class="px-3 py-2 text-xs font-bold text-gray-900">
                        Rp @{{ formatRupiah(item.subtotal) }}
                    </td>
                    <td v-if="editMode" class="px-3 py-2">
                        <button @click.stop="hapusBarang(index)"
                            class="px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 font-medium transition-all flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus
                        </button>
                    </td>
                </tr>
                <tr v-if="cart.length === 0">
                    <td colspan="6" class="px-3 py-8 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <p class="text-gray-500 text-sm font-medium">Keranjang masih kosong</p>
                        <p class="text-gray-400 text-xs mt-1">Scan barcode untuk menambah barang
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>