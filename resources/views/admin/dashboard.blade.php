@extends('admin.layout.app')

@section('content')
<div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-2">
    <div class="col-span-2">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Dashboard</h2>
        <p class="text-gray-500 mt-1">Ringkasan aktivitas toko anda</p>
    </div>
    <div class="text-sm md:text-lg font-bold flex justify-center items-center text-gray-500 bg-white px-3 py-2 rounded-md shadow-sm border border-gray-200">
        {{ now()->translatedFormat('l, d F Y') }}
    </div>
</div>

<div class="mb-6">
    <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
        <span class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
             <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </span>
        Ringkasan Aktivitas Hari Ini
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Transaksi Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-start justify-between relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-orange-400 to-orange-500"></div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Transaksi</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ number_format($transaksiHariIni) }}</h3>
                <p class="text-xs text-orange-600 mt-2 font-medium">Nota Terbit Hari Ini</p>
            </div>
            <div class="p-3 bg-orange-50 text-orange-600 rounded-lg group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        <!-- Omset Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-start justify-between relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-blue-400 to-blue-500"></div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Omset</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($omsetHariIni, 0, ',', '.') }}</h3>
                <p class="text-xs text-blue-600 mt-2 font-medium">Pendapatan Kotor</p>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <!-- Laba Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-start justify-between relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-green-400 to-green-500"></div>
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Laba Rugi</p>
                <h3 class="text-2xl font-bold text-green-600">Rp {{ number_format($labaHariIni, 0, ',', '.') }}</h3>
                <p class="text-xs text-green-600 mt-2 font-medium">Estimasi Laba Bersih</p>
            </div>
            <div class="p-3 bg-green-50 text-green-600 rounded-lg group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
        </div>
    </div>
</div>

<h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
    <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
         <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
        </svg>
    </span>
    Statistik Global Keanggotaan
</h3>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <!-- Card Total Barang -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-start justify-between relative overflow-hidden group hover:shadow-md transition-all">
        <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-blue-500 to-blue-600"></div>
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Barang</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ $totalBarang }}</h3>
            <p class="text-xs text-green-600 mt-2 font-medium flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
                Aktif & Terdaftar
            </p>
        </div>
        <div class="p-3 bg-blue-50 text-blue-600 rounded-lg group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </div>
    </div>

    <!-- Card Total Stok -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-start justify-between relative overflow-hidden group hover:shadow-md transition-all">
        <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-green-500 to-green-600"></div>
        <div>
           <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Stok Fisik</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalStok) }}</h3>
             <p class="text-xs text-gray-400 mt-2">Unit barang tersedia</p>
        </div>
         <div class="p-3 bg-green-50 text-green-600 rounded-lg group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
        </div>
    </div>

    <!-- Card Total Transaksi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-start justify-between relative overflow-hidden group hover:shadow-md transition-all">
        <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-purple-500 to-purple-600"></div>
        <div>
           <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Transaksi</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ $totalTransaksi }}</h3>
             <p class="text-xs text-purple-600 mt-2 font-medium">Seumur hidup</p>
        </div>
         <div class="p-3 bg-purple-50 text-purple-600 rounded-lg group-hover:scale-110 transition-transform">
             <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
    </div>
</div>
@endsection
