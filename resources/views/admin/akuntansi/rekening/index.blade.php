@extends('admin.layout.app')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Master Rekening</h2>
        <p class="text-sm text-gray-500">Kelola kode rekening dan chart of accounts</p>
    </div>
    <a href="{{ route('admin.rekening.create') }}" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Rekening
    </a>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3">Kode Rekening</th>
                    <th class="px-6 py-3">Nama Rekening</th>
                    <th class="px-6 py-3 text-center">Tipe</th>
                    <th class="px-6 py-3 text-center">Posisi</th>
                    <th class="px-6 py-3 text-right">Saldo</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rekenings as $item)
                @php
                    $isInduk = $item->tipe_rekening == 'induk';
                @endphp
                <tr class="{{ $isInduk ? 'bg-slate-50/80 font-bold' : '' }} hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-mono {{ $isInduk ? 'text-blue-800' : 'text-gray-900' }}">{{ $item->kode_rekening }}</td>
                    <td class="px-6 py-4 {{ $isInduk ? 'text-gray-900 uppercase tracking-tight' : 'text-gray-600' }}">{{ $item->nama_rekening }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($isInduk)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-slate-200 text-slate-700 uppercase">Induk</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase">Transaksi</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center text-xs">
                        @if($item->posisi_rekening == 'A')
                            <span class="font-bold text-green-600">AKTIVA</span>
                        @else
                            <span class="font-bold text-red-600">PASIVA</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right font-mono font-bold text-gray-900">
                        Rp {{ number_format($item->saldo ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.rekening.edit', $item->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('admin.rekening.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus nama rekening ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <p class="font-medium">Belum ada data rekening</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
