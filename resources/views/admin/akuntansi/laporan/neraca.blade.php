@extends('admin.layout.app')

@section('content')
    <style>
        .document-container {
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
        background-color: white;
        width: 100%;
        max-width: 210mm;
        margin: 0 auto;
        padding: 15px; /* Reduced padding for mobile */
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        color: #333;
    }
    .doc-header {
        text-align: center;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #333;
        padding-bottom: 0.5rem;
    }
    .doc-title {
        font-size: 20px; /* Smaller title */
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 4px;
        color: #111;
    }
    .doc-subtitle {
        font-size: 13px;
        color: #555;
    }
    .doc-section-title {
        font-size: 14px;
        font-weight: 700;
        margin-top: 15px;
        margin-bottom: 8px;
        text-transform: uppercase;
        border-bottom: 1px solid #eee;
        padding-bottom: 2px;
        color: #444;
    }
    .doc-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px; /* Smaller font for neatness */
    }
    .doc-table th, .doc-table td {
        border-bottom: 1px solid #eee; /* Cleaner row lines only */
        padding: 6px 4px;
    }
    .doc-table th {
        background-color: #f8f8f8;
        text-align: center;
        font-weight: 600;
        border-top: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
        color: #444;
    }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .font-bold { font-weight: bold; }
    
    /* Indentation & Hierarchy */
    .level-1 td:nth-child(2) { font-weight: 700; color: #000; font-size: 13px; }
    .level-2 td:nth-child(2) { font-weight: 600; padding-left: 15px; color: #333; }
    .level-3 td:nth-child(2) { padding-left: 30px; color: #555; }

    .total-row {
        font-weight: 700;
        background-color: #f4f4f4;
        border-top: 2px solid #ddd !important;
    }

    /* Mobile Responsive Tweaks */
    @media (min-width: 768px) {
        .document-container {
            padding: 20mm; /* Restore original padding on desktop */
            margin: 20px auto;
        }
        .doc-title { font-size: 24px; }
        .doc-table { font-size: 12px; }
    }
    
    @media print {
        .document-container { box-shadow: none; margin: 0; padding: 0; max-width: none; }
        .print\:hidden { display: none !important; }
        body { background: white; }
    }
    </style>

<div class="flex justify-between items-center mb-4 max-w-5xl mx-auto print:hidden">
    <form action="{{ route('admin.akuntansi.neraca') }}" method="GET" class="flex items-center gap-2">
        <input type="date" name="per_tanggal" value="{{ $perTanggal }}" class="px-3 py-1.5 border rounded-lg text-sm focus:ring-1 focus:ring-blue-500 outline-none">
        <button type="submit" class="bg-blue-600 text-white px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-blue-700">Filter</button>
    </form>
    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 flex items-center gap-2 text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak Neraca
    </button>
</div>

<div class="document-container">
    <div class="doc-header">
        <div class="doc-title">Laporan Neraca</div>
        <div class="doc-subtitle">Per Tanggal: {{ \Carbon\Carbon::parse($perTanggal)->translatedFormat('d F Y') }}</div>
    </div>

    <!-- AKTIVA -->
    <div class="doc-section-title">AKTIVA</div>
    <table class="doc-table">
        <thead>
            <tr>
                <th width="20%">Kode Akun</th>
                <th>Nama Akun</th>
                <th width="25%">Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($aktiva as $rek)
            <tr class="{{ $rek->class ?? '' }}">
                <td class="text-center font-mono">{{ $rek->KODER }}</td>
                <td>{{ $rek->NAMA }}</td>
                <td class="text-right">{{ number_format($rek->SALDO, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center py-4 text-gray-400">Tidak ada data Aktiva</td>
            </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="2" class="text-center">TOTAL AKTIVA</td>
                <td class="text-right">{{ number_format($totalAktiva, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <br>

    <!-- PASIVA -->
    <div class="doc-section-title">PASIVA</div>
    <table class="doc-table">
        <thead>
            <tr>
                <th width="20%">Kode Akun</th>
                <th>Nama Akun</th>
                <th width="25%">Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pasiva as $rek)
            <tr class="{{ $rek->class ?? '' }}">
                <td class="text-center font-mono">{{ $rek->KODER }}</td>
                <td>{{ $rek->NAMA }}</td>
                <td class="text-right">{{ number_format($rek->SALDO, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center py-4 text-gray-400">Tidak ada data Pasiva</td>
            </tr>
            @endforelse
            
            <tr class="level-2">
                <td class="text-center"></td>
                <td>Laba Rugi Tahun Ini</td>
                <td class="text-right">{{ number_format($totalAktiva - $totalPasivaReal, 0, ',', '.') }}</td>
            </tr>

            <tr class="total-row">
                <td colspan="2" class="text-center">TOTAL PASIVA</td>
                <td class="text-right">{{ number_format($totalPasiva, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- BALANCE CHECK -->
    <div style="margin-top: 30px; border-top: 1px dashed #ccc; padding-top: 15px; text-align: center;">
        @if(round($totalAktiva) == round($totalPasiva))
            <div style="font-weight: bold; color: #059669; font-size: 14px; letter-spacing: 0.05em;">[ NERACA SEIMBANG (BALANCED) ]</div>
        @else
            <div style="font-weight: bold; color: #dc2626; font-size: 14px; letter-spacing: 0.05em;">[ NERACA TIDAK SEIMBANG / UNBALANCED ]</div>
            <div style="color: #dc2626; font-size: 12px; margin-top: 4px;">Selisih: Rp {{ number_format(abs($totalAktiva - $totalPasiva), 0, ',', '.') }}</div>
        @endif
    </div>
</div>
@endsection
