@extends('admin.layout.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.barang.index') }}"
                class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Barang Baru</h2>
        </div>

        <form action="{{ route('admin.barang.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Informasi Dasar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Informasi Dasar</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Barang -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Kode Barang </label>
                        <input type="text" name="kode_barang" value="{{ old('kode_barang') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            placeholder="Kosongkan untuk generate otomatis">
                        @error('kode_barang')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Barcode -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Barcode / SKU</label>
                        <input type="text" name="barcode" value="{{ old('barcode') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            placeholder="Kosongkan untuk generate otomatis">
                        @error('barcode')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Barang -->
                    <div class="col-span-1 md:col-span-2 space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Nama Barang <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            placeholder="Contoh: Kopi Kapal Api 65gr" required>
                        @error('nama_barang')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Kategori</label>
                        <input type="text" name="kategori" value="{{ old('kategori') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            placeholder="Contoh: Minuman">
                    </div>

                    <!-- Supplier -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Supplier</label>
                        <select name="nama_supplier"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                            <option value="">Pilih Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->nama_supplier }}"
                                    {{ old('nama_supplier') == $supplier->nama_supplier ? 'selected' : '' }}>
                                    {{ $supplier->nama_supplier }}
                                </option>
                            @endforeach
                        </select>
                        @error('nama_supplier')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Satuan -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Satuan <span
                                class="text-red-500">*</span></label>
                        <select name="satuan_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white"
                            required>
                            <option value="">Pilih Satuan</option>
                            @foreach ($satuans as $satuan)
                                <option value="{{ $satuan->id }}"
                                    {{ old('satuan_id') == $satuan->id ? 'selected' : '' }}>
                                    {{ $satuan->nama_satuan }}
                                    {{ $satuan->singkatan ? '(' . $satuan->singkatan . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('satuan_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">
                            <a href="{{ route('admin.satuan.create') }}" target="_blank"
                                class="text-blue-600 hover:text-blue-800">
                                + Tambah satuan baru
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Harga & Stok -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Harga & Stok</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Harga Beli -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Harga Beli Terakhir <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="text" name="harga_beli_terakhir" value="{{ old('harga_beli_terakhir', 0) }}"
                                maxlength="12"
                                class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all mask-ribuan"
                                inputmode="numeric" required>
                        </div>
                    </div>

                    <!-- Harga Jual -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Harga Jual Normal <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="text" name="harga_jual_normal" value="{{ old('harga_jual_normal', 0) }}"
                                maxlength="12"
                                class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all mask-ribuan"
                                inputmode="numeric" required>
                        </div>
                    </div>

                    <!-- Stok -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Stok Awal <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="stok_sekarang" value="{{ old('stok_sekarang', 0) }}" maxlength="12"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all mask-ribuan"
                            inputmode="numeric" required>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.barang.index') }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                    Simpan Barang
                </button>
            </div>
        </form>
    </div>
@endsection
