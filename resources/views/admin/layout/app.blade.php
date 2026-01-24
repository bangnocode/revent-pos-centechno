<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REVENT - Centechno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { 
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
        }
        /* Table Sorting Styles */
        th.sortable {
            cursor: pointer;
            position: relative;
            padding-right: 20px !important;
        }
        th.sortable:after {
            content: '↕';
            position: absolute;
            right: 8px;
            color: #ccc;
            font-size: 0.8em;
        }
        th.sortable.sort-asc:after {
            content: '↑';
            color: #3b82f6;
        }
        th.sortable.sort-desc:after {
            content: '↓';
            color: #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen font-sans antialiased text-sm" x-data="{ mobileMenuOpen: false }">
    <!-- Overlay untuk mobile -->
    <div x-show="mobileMenuOpen" 
         @click="mobileMenuOpen = false"
         x-transition.opacity
         class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden">
    </div>
    
    <div class="flex h-screen overflow-hidden">
        <!-- ========== SIDEBAR DESKTOP ========== -->
        <div class="bg-slate-900 text-white w-56 flex-shrink-0 hidden md:flex flex-col overflow-hidden">
            <div class="p-4 border-b border-slate-800">
                <h1 class="text-lg font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-teal-400">Kasir CT</h1>
                <p class="text-xs text-slate-400 mt-0.5">Management System</p>
            </div>
            
            <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto overflow-x-hidden" 
                 x-data="{ 
                    openMaster: {{ request()->routeIs('admin.barang*') || request()->routeIs('admin.user*') || request()->routeIs('admin.supplier*') || request()->routeIs('admin.satuan*') ? 'true' : 'false' }},
                    openTransaksi: {{ request()->routeIs('admin.pembelian*') ? 'true' : 'false' }},
                    openLaporan: {{ request()->routeIs('admin.transaksi.*') ? 'true' : 'false' }}
                 }">
                
                <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm {{ request()->is('admin/dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <!-- Data Master -->
                <div class="space-y-1">
                    <button @click="openMaster = !openMaster" class="w-full flex items-center justify-between gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm text-slate-300 hover:bg-slate-800 hover:text-white group">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                            <span class="font-medium">Data Master</span>
                        </div>
                        <svg class="w-3 h-3 transition-transform" :class="openMaster ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="openMaster" x-transition class="pl-4 space-y-1">
                        <a href="{{ route('admin.barang.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.barang*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Barang</span>
                        </a>
                        <a href="{{ route('admin.user.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.user*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Kelola User</span>
                        </a>
                        <a href="{{ route('admin.supplier.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.supplier*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Supplier</span>
                        </a>
                        <a href="{{ route('admin.satuan.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.satuan*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Satuan</span>
                        </a>
                    </div>
                </div>

                <!-- Transaksi -->
                <div class="space-y-1">
                    <button @click="openTransaksi = !openTransaksi" class="w-full flex items-center justify-between gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm text-slate-300 hover:bg-slate-800 hover:text-white group">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            <span class="font-medium">Transaksi</span>
                        </div>
                        <svg class="w-3 h-3 transition-transform" :class="openTransaksi ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="openTransaksi" x-transition class="pl-4 space-y-1">
                        <a href="{{ route('admin.pembelian.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.pembelian*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Kulakan</span>
                        </a>
                    </div>
                </div>

                <!-- Laporan -->
                <div class="space-y-1">
                    <button @click="openLaporan = !openLaporan" class="w-full flex items-center justify-between gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm text-slate-300 hover:bg-slate-800 hover:text-white group">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-medium">Laporan / Rekap</span>
                        </div>
                        <svg class="w-3 h-3 transition-transform" :class="openLaporan ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="openLaporan" x-transition class="pl-4 space-y-1">
                        <a href="{{ route('admin.transaksi.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.transaksi.index') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Laporan Penjualan</span>
                        </a>
                        <a href="{{ route('admin.transaksi.laba-rugi') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.transaksi.laba-rugi') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Omset Detail</span>
                        </a>
                        <a href="{{ route('admin.transaksi.rekap-barang') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.transaksi.rekap-barang') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Omset Per Barang</span>
                        </a>
                        <a href="{{ route('admin.transaksi.rekap-tanggal') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.transaksi.rekap-tanggal') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Omset Per Tanggal</span>
                        </a>
                    </div>
                </div>

                <div class="pt-6 mt-auto">
                    <a href="{{ url('/pos') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg border border-slate-700 bg-slate-800/50 text-slate-300 hover:bg-green-600 hover:text-white hover:border-transparent transition-all text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">Buka Kasir</span>
                    </a>
                    
                    <form action="{{ route('logout') }}" method="POST" class="mt-2 text-center">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-red-400 hover:bg-slate-800 hover:text-red-300 transition-all text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- ========== SIDEBAR MOBILE ========== -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="md:hidden bg-slate-900 text-white w-56 flex-shrink-0 fixed inset-y-0 left-0 z-50 flex-col overflow-hidden">
            <div class="p-4 border-b border-slate-800">
                <h1 class="text-lg font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-teal-400">Kasir CT</h1>
                <p class="text-xs text-slate-400 mt-0.5">Management System</p>
            </div>
            
            <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto overflow-x-hidden"
                 x-data="{ 
                    openMaster: {{ request()->routeIs('admin.barang*') || request()->routeIs('admin.supplier*') || request()->routeIs('admin.satuan*') ? 'true' : 'false' }},
                    openTransaksi: {{ request()->routeIs('admin.pembelian*') ? 'true' : 'false' }},
                    openLaporan: {{ request()->routeIs('admin.transaksi.*') ? 'true' : 'false' }}
                 }">
                <a href="{{ url('/admin/dashboard') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm {{ request()->is('admin/dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <!-- Data Master -->
                <div class="space-y-1">
                    <button @click="openMaster = !openMaster" class="w-full flex items-center justify-between gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm text-slate-300 hover:bg-slate-800 hover:text-white group">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                            <span class="font-medium">Data Master</span>
                        </div>
                        <svg class="w-3 h-3 transition-transform" :class="openMaster ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="openMaster" x-transition class="pl-4 space-y-1">
                        <a href="{{ route('admin.barang.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.barang*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Barang</span>
                        </a>
                        <a href="{{ route('admin.user.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.user*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Kelola User</span>
                        </a>
                        <a href="{{ route('admin.supplier.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.supplier*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Supplier</span>
                        </a>
                        <a href="{{ route('admin.satuan.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.satuan*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Satuan</span>
                        </a>
                    </div>
                </div>

                <!-- Transaksi -->
                <div class="space-y-1">
                    <button @click="openTransaksi = !openTransaksi" class="w-full flex items-center justify-between gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm text-slate-300 hover:bg-slate-800 hover:text-white group">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            <span class="font-medium">Transaksi</span>
                        </div>
                        <svg class="w-3 h-3 transition-transform" :class="openTransaksi ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="openTransaksi" x-transition class="pl-4 space-y-1">
                        <a href="{{ route('admin.pembelian.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.pembelian*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Kulakan</span>
                        </a>
                    </div>
                </div>

                <!-- Laporan -->
                <div class="space-y-1">
                    <button @click="openLaporan = !openLaporan" class="w-full flex items-center justify-between gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm text-slate-300 hover:bg-slate-800 hover:text-white group">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-medium">Laporan / Rekap</span>
                        </div>
                        <svg class="w-3 h-3 transition-transform" :class="openLaporan ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="openLaporan" x-transition class="pl-4 space-y-1">
                        <a href="{{ route('admin.transaksi.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.transaksi.index') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Laporan Penjualan</span>
                        </a>
                        <a href="{{ route('admin.transaksi.laba-rugi') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.transaksi.laba-rugi') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Omset Detail</span>
                        </a>
                        <a href="{{ route('admin.transaksi.rekap-barang') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.transaksi.rekap-barang') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Omset Per Barang</span>
                        </a>
                        <a href="{{ route('admin.transaksi.rekap-tanggal') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ request()->routeIs('admin.transaksi.rekap-tanggal') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white' }}">
                            <span>• Omset Per Tanggal</span>
                        </a>
                    </div>
                </div>

                <div class="pt-6 mt-auto">
                    <a href="{{ url('/pos') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg border border-slate-700 bg-slate-800/50 text-slate-300 hover:bg-green-600 hover:text-white hover:border-transparent transition-all text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">Buka Kasir</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2 text-center">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-red-400 hover:bg-slate-800 hover:text-red-300 transition-all text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
                <!-- Tombol tutup khusus mobile -->
                <div class="pt-4 mt-4 border-t border-slate-800">
                    <button @click="mobileMenuOpen = false" 
                            class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg bg-slate-800 text-slate-300 hover:bg-slate-700 hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>Tutup Menu</span>
                    </button>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header Mobile -->
            <header class="bg-white shadow-sm border-b border-gray-100 md:hidden">
                <div class="px-3 py-2.5 flex items-center justify-between">
                    <h1 class="text-lg font-bold text-gray-800">Kasir CT</h1>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="p-1.5 text-gray-600 rounded hover:bg-gray-100 transition-colors">
                        <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4">
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-3 rounded-r shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-4 w-4 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-2.5">
                                <p class="text-xs text-green-700">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Handle resize window - reset state jika berpindah ke desktop
        window.addEventListener('resize', function() {
            const alpineData = Alpine.$data(document.querySelector('[x-data]'));
            if (window.innerWidth >= 768 && alpineData) {
                // Di desktop, pastikan mobile menu tertutup
                alpineData.mobileMenuOpen = false;
            }
        });
        
        // Auto close menu mobile saat klik link (kecuali di desktop)
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('a');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    const alpineData = Alpine.$data(document.querySelector('[x-data]'));
                    if (window.innerWidth < 768 && alpineData) {
                        alpineData.mobileMenuOpen = false;
                    }
                });
            });

            // --- Thousand Formatter Helpers ---
            window.formatNumberRibuan = function(n) {
                if (n === null || n === undefined || n === "") return "";
                // Remove everything except digits
                let value = String(n).replace(/\D/g, "");
                // Apply thousand separators
                return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            };

            window.unformatNumberRibuan = function(n) {
                if (n === null || n === undefined || n === "") return "";
                return String(n).replace(/\D/g, "");
            };

            // Auto apply to elements with .mask-ribuan
            const applyMask = (el) => {
                el.value = formatNumberRibuan(el.value);
                el.addEventListener('input', function(e) {
                    const originalValue = unformatNumberRibuan(this.value);
                    this.value = formatNumberRibuan(originalValue);
                });
            };

            document.querySelectorAll('.mask-ribuan').forEach(applyMask);

            // Clean up before form submit
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    this.querySelectorAll('.mask-ribuan').forEach(input => {
                        input.value = unformatNumberRibuan(input.value);
                    });
                });
            });

            // --- Table Sorting Implementation ---
            const enableTableSorting = () => {
                document.querySelectorAll('table').forEach(table => {
                    if (table.dataset.sortableEnabled) return;
                    
                    const headers = table.querySelectorAll('thead th');
                    headers.forEach((header, index) => {
                        // Skip if header has no text or explicitly excluded
                        if (!header.innerText.trim() || header.dataset.noSort !== undefined) return;
                        
                        header.classList.add('sortable');
                        header.addEventListener('click', () => {
                            const isAsc = header.classList.contains('sort-asc');
                            
                            // Reset other headers
                            headers.forEach(h => h.classList.remove('sort-asc', 'sort-desc'));
                            
                            if (isAsc) {
                                header.classList.add('sort-desc');
                                sortTable(table, index, false);
                            } else {
                                header.classList.add('sort-asc');
                                sortTable(table, index, true);
                            }
                        });
                    });
                    
                    table.dataset.sortableEnabled = "true";
                });
            };

            const sortTable = (table, column, asc = true) => {
                const tbody = table.querySelector('tbody');
                if (!tbody) return;
                
                const rows = Array.from(tbody.querySelectorAll('tr'));
                // Skip if only empty state row
                if (rows.length === 1 && rows[0].querySelector('td[colspan]')) return;

                const sortedRows = rows.sort((a, b) => {
                    let aVal = a.querySelectorAll('td')[column]?.innerText.trim() || "";
                    let bVal = b.querySelectorAll('td')[column]?.innerText.trim() || "";
                    
                    // Try to parse as number (remove Rp, dots, etc)
                    const cleanNum = (str) => {
                        // Remove Rp, separators, units (pcs, gr, etc)
                        let val = str.replace(/[Rp.\s]/g, '').replace(/,/g, '.');
                        // Extract number part if trailing units
                        let match = val.match(/^-?\d+(\.\d+)?/);
                        return match ? parseFloat(match[0]) : val.toLowerCase();
                    };

                    const aNum = cleanNum(aVal);
                    const bNum = cleanNum(bVal);

                    if (typeof aNum === 'number' && typeof bNum === 'number') {
                        return asc ? aNum - bNum : bNum - aNum;
                    }
                    
                    return asc ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
                });

                // Clear and append
                tbody.innerHTML = '';
                sortedRows.forEach(row => tbody.appendChild(row));
            };

            // Initial call
            enableTableSorting();

            // Support for dynamically added tables (e.g. via Livewire or AJAX modals)
            const observer = new MutationObserver(() => {
                enableTableSorting();
            });
            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>
</body>
</html>