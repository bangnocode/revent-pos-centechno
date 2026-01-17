<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revent - Centechno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { 
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
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
            
            <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto overflow-x-hidden">
                <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm {{ request()->is('admin/dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <div class="pt-3 pb-1.5">
                    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Data</p>
                </div>

                <a href="{{ route('admin.barang.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm {{ request()->routeIs('admin.barang*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="font-medium">Barang</span>
                </a>

                <a href="{{ route('admin.supplier.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm {{ request()->routeIs('admin.supplier*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-medium">Supplier</span>
                </a>

                <div class="pt-3 pb-1.5">
                    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Transaksi</p>
                </div>
                
                <a href="{{ route('admin.pembelian.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm {{ request()->routeIs('admin.pembelian*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-medium">Kulakan</span>
                </a>

                <a href="{{ route('admin.transaksi.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm {{ request()->routeIs('admin.transaksi*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium">Laporan Penjualan</span>
                </a>

                <div class="pt-6 mt-auto">
                    <a href="{{ url('/pos') }}" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg border border-slate-700 bg-slate-800/50 text-slate-300 hover:bg-green-600 hover:text-white hover:border-transparent transition-all text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">Buka Revent</span>
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
            
            <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto overflow-x-hidden">
                <a href="{{ url('/admin/dashboard') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm {{ request()->is('admin/dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <div class="pt-3 pb-1.5">
                    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Data</p>
                </div>

                <a href="{{ route('admin.barang.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm {{ request()->routeIs('admin.barang*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="font-medium">Barang</span>
                </a>

                <a href="{{ route('admin.supplier.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm {{ request()->routeIs('admin.supplier*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-medium">Supplier</span>
                </a>

                <div class="pt-3 pb-1.5">
                    <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Transaksi</p>
                </div>
                
                <a href="{{ route('admin.pembelian.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm {{ request()->routeIs('admin.pembelian*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-medium">Kulakan </span>
                </a>

                <a href="{{ route('admin.transaksi.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg transition-all text-sm {{ request()->routeIs('admin.transaksi*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium">Laporan Penjualan</span>
                </a>

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
        });
    </script>
</body>
</html>