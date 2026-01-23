<!-- POS Header Component -->
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
                <form action="{{ route('logout') }}" method="POST" class="m-0 flex items-center">
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