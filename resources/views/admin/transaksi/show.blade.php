@extends('admin.layout.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.transaksi.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Detail Transaksi</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header Faktur -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-start">
            <div>
                <h3 class="text-lg font-bold text-gray-900">{{ $transaksi->nomor_faktur }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('l, d F Y H:i') }}</p>
            </div>
            <div class="text-right">
                 @if($transaksi->status_pembayaran == 'lunas')
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold border border-green-200">LUNAS</span>
                @elseif($transaksi->status_pembayaran == 'hutang')
                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold border border-red-200">BELUM LUNAS</span>
                @else
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold border border-yellow-200">{{ strtoupper($transaksi->status_pembayaran) }}</span>
                @endif
            </div>
        </div>

        <!-- Info Pelanggan & Kasir -->
        <div class="px-6 py-4 grid grid-cols-2 gap-4 border-b border-gray-100">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Pelanggan</p>
                <div class="font-medium text-gray-900">{{ $transaksi->nama_pelanggan ?: 'Pelanggan Umum' }}</div>
                <div class="text-sm text-gray-500">{{ $transaksi->kode_pelanggan ?: '-' }}</div>
            </div>
            <div>
                 <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Kasir</p>
                <div class="font-medium text-gray-900">{{ $transaksi->id_operator }}</div>
            </div>
        </div>

        <!-- Daftar Item -->
        <div class="px-6 py-4">
            <h4 class="text-sm font-bold text-gray-800 mb-3">Rincian Item</h4>
            <div class="border rounded-lg overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 border-b">
                        <tr>
                            <th class="px-4 py-2 text-left">Barang</th>
                            <th class="px-4 py-2 text-right">Harga</th>
                            <th class="px-4 py-2 text-center">Qty</th>
                            <th class="px-4 py-2 text-right">Diskon</th>
                            <th class="px-4 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($transaksi->details as $detail)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $detail->nama_barang }}</div>
                                <div class="text-xs text-gray-500">{{ $detail->kode_barang }}</div>
                            </td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">{{ $detail->jumlah }} {{ $detail->satuan }}</td>
                            <td class="px-4 py-3 text-right text-red-500">
                                @if($detail->diskon_item > 0)
                                    - Rp {{ number_format($detail->diskon_item, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($detail->subtotal_item, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rincian Pembayaran -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            <div class="flex justify-end">
                <div class="w-full md:w-1/2 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($transaksi->diskon_transaksi > 0)
                    <div class="flex justify-between text-sm text-red-600">
                        <span>Diskon Tambahan</span>
                        <span>- Rp {{ number_format($transaksi->diskon_transaksi, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="border-t border-gray-200 my-2 pt-2 flex justify-between items-center">
                        <span class="font-bold text-gray-800 text-base">Total Transaksi</span>
                        <span class="font-bold text-blue-600 text-xl">Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</span>
                    </div>
                     <div class="flex justify-between text-sm pt-2">
                        <span class="text-gray-600">Metode Pembayaran</span>
                        <span class="font-medium capitalize">{{ $transaksi->metode_pembayaran }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Dibayar</span>
                        <span class="font-medium">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
                    </div>
                     <div class="flex justify-between text-sm font-medium {{ $transaksi->kembalian < 0 ? 'text-red-600' : 'text-green-600' }}">
                        <span>{{ $transaksi->kembalian < 0 ? 'Sisa Hutang' : 'Kembalian' }}</span>
                        <span>Rp {{ number_format(abs($transaksi->kembalian), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Footer -->
         <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
             <a href="{{ url('/pos/print-invoice/' . $transaksi->nomor_faktur) }}" target="_blank" class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Struk
             </a>
         </div>
    </div>
</div>
@endsection
