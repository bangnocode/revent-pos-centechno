
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Revent - Centechno</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Heroicons CDN -->
    <script src="https://unpkg.com/@heroicons/vue@2.0.0/outline/index.js"></script>

    <!-- Thermal Printer Library -->
    <script src="https://cdn.jsdelivr.net/npm/escpos@3.0.0-alpha.6/dist/escpos.min.js"></script>

    <style>
        [v-cloak] {
            display: none;
        }

        @media print {
            @page {
                margin: 0;
                size: 80mm auto;
            }

            body {
                font-size: 12px;
                padding: 10px;
            }

            button {
                display: none;
            }
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        .focused-item {
            background-color: #fef3c7 !important;
            outline: 2px solid #f59e0b;
        }

        .focused-search-item {
            background-color: #eff6ff !important;
            outline: 2px solid #3b82f6;
        }

        .scrollbar-thin {
            scrollbar-width: thin;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div id="app" v-cloak>
        <!-- Header Modern Simple - Compact -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 shadow">
            <div class="px-3 sm:px-4 py-2">
                <!-- Top Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-white rounded flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-white">Revent</h1>
                            <p class="text-xs text-blue-100">Transaksi Penjualan</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <div
                            class="flex items-center gap-1.5 text-xs text-white bg-white bg-opacity-20 rounded px-2.5 py-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="font-medium">@auth {{ auth()->user()->name }} @else Guest @endauth</span>
                            <span class="text-blue-200 text-xs">•</span>
                            <span>@{{ currentDate }}</span>
                            <span>@{{ currentTime }}</span>
                        </div>
                        
                        @auth
                        <form action="{{ route('logout') }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white rounded px-2.5 py-1.5 text-xs font-medium transition-colors flex items-center gap-1" title="Logout">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-600 text-white rounded px-2.5 py-1.5 text-xs font-medium transition-colors flex items-center gap-1" title="Login">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Login</span>
                        </a>
                        @endauth
                    </div>
                </div>

                <!-- Keyboard Shortcuts - Compact -->
                <div class="lg:flex flex-wrap gap-1 hidden">
                    <div
                        class="flex items-center gap-1 bg-white bg-opacity-10 hover:bg-opacity-20 transition-all rounded px-1.5 py-1 text-xs text-white">
                        <kbd class="bg-white bg-opacity-20 px-1 py-0.5 rounded text-xs font-mono font-semibold">F2</kbd>
                        <span class="hidden sm:inline">Fokus Input Kode (F2)</span>
                    </div>
                    <div
                        class="flex items-center gap-1 bg-white bg-opacity-10 hover:bg-opacity-20 transition-all rounded px-1.5 py-1 text-xs text-white">
                        <kbd class="bg-white bg-opacity-20 px-1 py-0.5 rounded text-xs font-mono font-semibold">F3</kbd>
                        <span class="hidden sm:inline">Cari Barang (F3)</span>
                    </div>
                    <div
                        class="flex items-center gap-1 bg-white bg-opacity-10 hover:bg-opacity-20 transition-all rounded px-1.5 py-1 text-xs text-white">
                        <kbd class="bg-white bg-opacity-20 px-1 py-0.5 rounded text-xs font-mono font-semibold">F8</kbd>
                        <span class="hidden sm:inline">Edit (F8)</span>
                    </div>
                    <div
                        class="flex items-center gap-1 bg-white bg-opacity-10 hover:bg-opacity-20 transition-all rounded px-1.5 py-1 text-xs text-white">
                        <kbd class="bg-white bg-opacity-20 px-1 py-0.5 rounded text-xs font-mono font-semibold">F9</kbd>
                        <span class="hidden sm:inline">Bayar (F9)</span>
                    </div>
                    <div
                        class="flex items-center gap-1 bg-white bg-opacity-10 hover:bg-opacity-20 transition-all rounded px-1.5 py-1 text-xs text-white">
                        <kbd
                            class="bg-white bg-opacity-20 px-1 py-0.5 rounded text-xs font-mono font-semibold">ESC</kbd>
                        <span class="hidden sm:inline">Batal (ESC)</span>
                    </div>
                </div>
            </div>
        </div>

        <main class="px-3 sm:px-4 py-3 bg-gray-50 min-h-screen">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 max-w-7xl mx-auto">
                <!-- Kolom Kiri -->
                <div class="lg:col-span-2 space-y-3">
                    <!-- Input Section -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center gap-1.5 mb-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            <label class="text-xs font-semibold text-gray-700 flex gap-2">Scan Barcode / Input Kode <span class="hidden lg:flex">(Enter untuk
                                tambah)</span></label>
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

                    <!-- Shopping Cart -->
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
                                        @{{ cart.length }} item • @{{ totalQty }} pcs
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
                                    <span class="font-semibold text-amber-800 text-xs">Mode Edit Aktif - Gunakan
                                        shortcut:</span>
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
                                            'bg-blue-50 border-l-3 border-blue-500': editMode && editSelectedIndex ===
                                                index,
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
                                                    @{{ item.jumlah }} @{{ item.satuan }}
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
                                                @{{ item.jumlah }} @{{ item.satuan }}
                                            </div>
                                        </td>
                                        <td v-if="editMode" class="px-3 py-2">
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 text-xs">Rp</span>
                                                </div>
                                                <input :value="item.diskon_item" type="number"
                                                    min="0" step="100"
                                                    @input="setDiskonItem(index, $event.target.value)"
                                                    :max="item.harga_satuan * item.jumlah"
                                                    class="w-24 pl-6 pr-2 py-1 border border-gray-300 rounded text-xs text-right">
                                            </div>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                Maks: Rp @{{ formatRupiah   (item.harga_satuan * item.jumlah) }}
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
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-3">
                    <!-- Ringkasan Transaksi -->
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
                                <span class="text-sm font-semibold text-gray-900">@{{ totalQty }} item</span>
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
                </div>
        </main>

        <!-- Modal Edit QTY - Compact -->
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
                            <div class="font-bold text-blue-600 text-sm">@{{ selectedItem?.stok_sekarang }} @{{ selectedItem?.satuan }}</div>
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

        <!-- Modal Pembayaran - Compact -->
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
                        <input v-model="pembayaran.nama_pelanggan" type="text"
                            class="w-full px-2.5 py-1.5 border border-gray-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none text-sm"
                            placeholder="Pelanggan Umum" :disabled="isProcessing" />
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

                    <!-- Di modal pembayaran, sebelum total transaksi -->
                    <div class="bg-yellow-50 p-3 rounded border border-yellow-200">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Diskon Transaksi (Global)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">Rp</span>
                            </div>
                            <input :value="diskonInput" @input="handleDiskonInput" type="text"
                                class="w-full pl-10 pr-3 py-1.5 border border-gray-300 rounded text-right"
                                placeholder="0" :disabled="isProcessing" maxlength="15">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Maks: Rp @{{ formatRupiah(subtotalSetelahDiskonItem) }}
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
                        <input :value="formatRupiah(pembayaran.uang_dibayar)" @input="formatUangDibayar"
                            @keydown.enter.prevent="!isProcessing && prosesPembayaran()" type="text" maxlength="12"
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

        <!-- Modal Pencarian Barang - Compact -->
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
                                'border border-blue-500 bg-blue-50': selectedSearchIndex === index,
                                'border border-gray-200 hover:bg-gray-50': selectedSearchIndex !== index
                            }"
                            class="rounded-lg p-3 cursor-pointer transition-all"
                            @click="tambahBarangDariPencarian(barang)" @mouseenter="selectedSearchIndex = index">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 text-sm">@{{ barang.nama_barang }}</h3>
                                    <div class="flex gap-2 mt-1 text-xs text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                            </svg>
                                            @{{ barang.kode_barang }}
                                        </span>
                                        <span v-if="barang.barcode" class="text-gray-500">| Barcode:
                                            @{{ barang.barcode }}</span>
                                    </div>
                                    <div class="mt-1">
                                        <span
                                            class="inline-flex items-center gap-0.5 px-1.5 py-0.5 bg-gray-100 text-gray-700 rounded text-xs">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                            Stok: @{{ parseInt(barang.stok_sekarang) || 0 }} @{{ barang.satuan }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right ml-3">
                                    <div class="text-lg font-bold text-blue-600">
                                        Rp @{{ formatRupiah(barang.harga_jual_normal) }}
                                    </div>
                                    <button
                                        class="mt-1.5 px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                                        Tambah
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
    </div>

    <!-- Vue 3 Production CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <!-- Axios CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="{{ asset('js/pos-logic.js') }}"></script>
</body>

