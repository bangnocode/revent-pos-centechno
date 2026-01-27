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
            <h2 class="text-2xl font-bold text-gray-800">Edit Rekening</h2>
        </div>

        <form action="{{ route('admin.rekening.update', $rekening->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Informasi Rekening</h3>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Kode Rekening (Read-Only) -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Kode Rekening</label>
                        <input type="text" value="{{ $rekening->kode_rekening }}" 
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-500 font-mono"
                            disabled>
                        <p class="text-xs text-gray-400 mt-1">Kode rekening tidak dapat diubah</p>
                    </div>

                    <!-- Nama Rekening -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Nama Rekening <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_rekening" value="{{ old('nama_rekening', $rekening->nama_rekening) }}" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            required>
                        @error('nama_rekening')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipe Rekening (Read-Only) -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Tipe Rekening</label>
                        <input type="text" value="{{ $rekening->tipe_rekening == 'induk' ? 'Rekening Induk' : 'Rekening Transaksi' }}" 
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-500"
                            disabled>
                    </div>

                    <!-- Posisi Rekening (Read-Only) -->
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Posisi Rekening</label>
                        <input type="text" value="{{ $rekening->posisi_rekening == 'A' ? 'Aktiva (A)' : 'Pasiva (P)' }}" 
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg font-bold {{ $rekening->posisi_rekening == 'A' ? 'text-green-600' : 'text-red-600' }}"
                            disabled>
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
                    Update Rekening
                </button>
            </div>
        </form>
    </div>
@endsection
