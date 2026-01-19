@extends('admin.layout.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.satuan.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Tambah Satuan Baru</h2>
    </div>

    <form action="{{ route('admin.satuan.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6 pb-2 border-b border-gray-100">Informasi Satuan</h3>

            <div class="space-y-6">
                <!-- Nama Satuan -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Nama Satuan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_satuan" value="{{ old('nama_satuan') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="Contoh: Kilogram, Liter, Pieces" required>
                    @error('nama_satuan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Singkatan -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Singkatan</label>
                    <input type="text" name="singkatan" value="{{ old('singkatan') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                        placeholder="Contoh: kg, L, pcs">
                    @error('singkatan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Opsional, untuk mempersingkat tampilan</p>
                </div>

                <!-- Status Aktif -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="flex items-center">
                        <input type="hidden" name="status_aktif" value="0">
                        <input type="checkbox" id="status_aktif" name="status_aktif" value="1" {{ old('status_aktif', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="status_aktif" class="ml-2 block text-sm text-gray-700">
                            Aktifkan satuan ini
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Satuan aktif akan muncul di dropdown pilihan</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.satuan.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Satuan
            </button>
        </div>
    </form>
</div>
@endsection