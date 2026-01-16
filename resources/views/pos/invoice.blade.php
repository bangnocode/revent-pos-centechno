<!DOCTYPE html>
<html>

<head>
    <title>Invoice #{{ $transaksi->nomor_faktur }}</title>
    <style>
        @media print {
            body {
                font-family: 'Courier New', monospace;
                width: 80mm;
                margin: 0;
                padding: 10px;
                font-size: 14px;
            }

            .no-print {
                display: none;
            }

            @page {
                margin: 0;
            }
        }

        body {
            font-family: Arial, sans-serif;
            max-width: 80mm;
            margin: 0 auto;
            padding: 20px;
        }

        .header,
        .footer {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        td {
            padding: 5px 0;
            border-bottom: 1px dashed #ccc;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            border-top: 2px solid #000;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>TOKO CT</h3>
        <p>Jl. Contoh No. 123</p>
        <p>Telp: 0812-3456-7890</p>
        <hr>
        <p><strong>FAKTUR: {{ $transaksi->nomor_faktur }}</strong></p>
        <p>Tanggal: {{ date('d/m/Y H:i', strtotime($transaksi->tanggal_transaksi)) }}</p>
        <p>Kasir: {{ $transaksi->id_operator }}</p>
        <p>Pelanggan: {{ $transaksi->nama_pelanggan }}</p>
    </div>

    <hr>

    <table>
        <thead>
            <tr>
                <td><strong>Barang</strong></td>
                <td class="text-right"><strong>Qty</strong></td>
                <td class="text-right"><strong>Subtotal</strong></td>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->details as $item)
                <tr>
                    <td>{{ $item->nama_barang }}</td>
                    <td class="text-right">{{ $item->jumlah }} {{ $item->satuan }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal_item, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <table>
        <tr>
            <td>Subtotal:</td>
            <td class="text-right">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</td>
        </tr>

        @php
            // Hitung total diskon item dari detail
            $totalDiskonItem = $transaksi->details->sum('diskon_item');
        @endphp

        @if ($totalDiskonItem > 0)
            <tr>
                <td>Diskon Item:</td>
                <td class="text-right">- Rp {{ number_format($totalDiskonItem, 0, ',', '.') }}</td>
            </tr>
        @endif

        @if ($transaksi->diskon_transaksi > 0)
            <tr>
                <td>Diskon Transaksi:</td>
                <td class="text-right">- Rp {{ number_format($transaksi->diskon_transaksi, 0, ',', '.') }}</td>
            </tr>
        @endif

        <tr>
            <td>Total:</td>
            <td class="text-right"><strong>Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</strong>
            </td>
        </tr>
        <tr>
            <td>Bayar:</td>
            <td class="text-right">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>Kembali:</td>
            <td class="text-right">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</td>
        </tr>
    </table>

    <hr>

    <div class="footer">
        <p><strong>Metode: {{ strtoupper($transaksi->metode_pembayaran) }}</strong></p>
        <p>Terima kasih telah berbelanja</p>
        <p>*** Barang yang sudah dibeli tidak dapat ditukar ***</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; margin: 5px;">🖨️ Print</button>
        <button onclick="window.close()" style="padding: 10px 20px; margin: 5px;">❌ Tutup</button>
    </div>

    <script>
        // Auto print setelah window terbuka
        window.onload = function() {
            @if (request()->has('autoprint'))
                window.print();
            @endif
        };
    </script>
</body>

</html>
