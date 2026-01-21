<!DOCTYPE html>
<html>

<head>
    <title>Invoice #{{ $transaksi->nomor_faktur }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            width: 72mm; /* Standard width for 80mm printers but safe for 58mm with scaling */
            margin: 0 auto;
            padding: 5px;
            font-size: 12px;
            color: #000;
        }

        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none;
            }

            @page {
                margin: 0;
            }
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h3 {
            font-size: 16px;
            margin-bottom: 2px;
        }

        .header p {
            font-size: 11px;
            line-height: 1.2;
        }

        .info {
            font-size: 11px;
            margin-bottom: 8px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }

        .info table {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .items-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .totals {
            margin-top: 5px;
        }

        .totals td {
            padding: 2px 0;
        }

        .total-row {
            font-weight: bold;
            font-size: 13px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
            border-top: 1px dashed #000;
            padding-top: 8px;
        }

        .btn-container {
            margin-top: 20px;
            text-align: center;
        }

        .btn {
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 4px;
            border: 1px solid #ccc;
            background: #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>TOKO CT</h3>
        <p>Jl. Contoh No. 123, Kota Anda</p>
        <p>Telp: 0812-3456-7890</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td>No: {{ $transaksi->nomor_faktur }}</td>
                <td class="text-right">{{ date('d/m/Y H:i', strtotime($transaksi->tanggal_transaksi)) }}</td>
            </tr>
            <tr>
                <td>Kasir: {{ $transaksi->id_operator }}</td>
                <td class="text-right">Plg: {{ $transaksi->nama_pelanggan }}</td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr style="border-bottom: 1px dashed #000;">
                <td style="width: 45%;"><strong>Item</strong></td>
                <td class="text-right" style="width: 20%;"><strong>Qty</strong></td>
                <td class="text-right" style="width: 35%;"><strong>Total</strong></td>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->details as $item)
                <tr>
                    <td>{{ $item->nama_barang }}</td>
                    <td class="text-right">{{ (int)$item->jumlah }}</td>
                    <td class="text-right">{{ number_format($item->subtotal_item, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table class="totals">
        <tr>
            <td>Subtotal</td>
            <td class="text-right">{{ number_format($transaksi->subtotal, 0, ',', '.') }}</td>
        </tr>

        @php
            $totalDiskonItem = $transaksi->details->sum('diskon_item');
        @endphp

        @if ($totalDiskonItem > 0)
            <tr>
                <td>Diskon Item</td>
                <td class="text-right">-{{ number_format($totalDiskonItem, 0, ',', '.') }}</td>
            </tr>
        @endif

        @if ($transaksi->diskon_transaksi > 0)
            <tr>
                <td>Diskon Trans</td>
                <td class="text-right">-{{ number_format($transaksi->diskon_transaksi, 0, ',', '.') }}</td>
            </tr>
        @endif

        <tr class="total-row">
            <td>Grand Total</td>
            <td class="text-right">{{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Bayar ({{ strtoupper($transaksi->metode_pembayaran) }})</td>
            <td class="text-right">{{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="text-right">{{ number_format($transaksi->kembalian, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
        <p>Printed: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="no-print btn-container">
        <button class="btn" onclick="window.print()">🖨️ Print</button>
        <button class="btn" onclick="window.close()">❌ Tutup</button>
    </div>

    <script>
        window.onload = function() {
            @if (request()->has('autoprint'))
                setTimeout(function() {
                    window.print();
                    // Optional: auto close after print
                    // setTimeout(function() { window.close(); }, 500);
                }, 500);
            @endif
        };
    </script>
</body>

</html>
