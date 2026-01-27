@extends('admin.layout.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.rekening.index') }}"
                class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Rekening Baru</h2>
        </div>

        <form action="{{ route('admin.rekening.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Informasi Rekening -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Informasi Rekening</h3>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Kode Rekening -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Kode Rekening <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_rekening" value="{{ old('kode_rekening') }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all font-mono"
                            placeholder="Contoh: 1-00000" required>
                        @error('kode_rekening')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-gray-400 mt-1">Format: X-000000, X-X0000, X-X000X</p>
                    </div>

                    <!-- Nama Rekening -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Nama Rekening <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_rekening" value="{{ old('nama_rekening') }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            placeholder="Contoh: Kas Besar" required>
                        @error('nama_rekening')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipe Rekening -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Tipe Rekening <span class="text-red-500">*</span></label>
                        <div class="flex flex-col sm:flex-row gap-4 mt-2">
                            <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors flex-1">
                                <input type="radio" name="tipe_rekening" value="induk" class="w-4 h-4 text-blue-600 focus:ring-blue-500" {{ old('tipe_rekening') == 'induk' ? 'checked' : '' }} required>
                                <div>
                                    <span class="block text-sm font-medium text-gray-900">Rekening Induk</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors flex-1">
                                <input type="radio" name="tipe_rekening" value="transaksi" class="w-4 h-4 text-blue-600 focus:ring-blue-500" {{ old('tipe_rekening') == 'transaksi' ? 'checked' : '' }}>
                                <div>
                                    <span class="block text-sm font-medium text-gray-900">Rekening Transaksi</span>
                                </div>
                            </label>
                        </div>
                        @error('tipe_rekening')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Posisi Rekening -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Posisi Rekening <span class="text-red-500">*</span></label>
                        <div class="flex flex-col sm:flex-row gap-4 mt-2">
                            <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-green-50 transition-colors flex-1">
                                <input type="radio" name="posisi_rekening" value="A" class="w-4 h-4 text-green-600 focus:ring-green-500" {{ old('posisi_rekening') == 'A' ? 'checked' : '' }} required>
                                <div>
                                    <span class="block text-sm font-bold text-green-700">Aktiva (A)</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-red-50 transition-colors flex-1">
                                <input type="radio" name="posisi_rekening" value="P" class="w-4 h-4 text-red-600 focus:ring-red-500" {{ old('posisi_rekening') == 'P' ? 'checked' : '' }}>
                                <div>
                                    <span class="block text-sm font-bold text-red-700">Pasiva (P)</span>
                                </div>
                            </label>
                        </div>
                        @error('posisi_rekening')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.rekening.index') }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                    Simpan Rekening
                </button>
            </div>
        </form>
    </div>
@endsection
