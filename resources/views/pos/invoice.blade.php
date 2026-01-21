<!DOCTYPE html>
<html>

<head>
    <title>Invoice #{{ $transaksi->nomor_faktur }}</title>
    <style>
        @media print {
            body {
                font-family: 'Courier New', monospace;
                width: 55mm; /* Adjusted for smallest supported paper (57.5mm) */
                margin: 0 auto;
                padding: 5px;
                font-size: 11px; /* Slightly smaller font for narrower paper */
            }

            .no-print {
                display: none;
            }

            @page {
                margin: 0;
            }
            
            hr {
                border-top: 1px dashed #000;
                border-bottom: none;
            }
        }

        body {
            font-family: Arial, sans-serif;
            max-width: 55mm;
            margin: 0 auto;
            padding: 20px;
            font-size: 12px;
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
            padding: 3px 0;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            border-top: 1px dashed #000;
        }
        
        hr {
            border-top: 1px dashed #ccc;
            border-bottom: none;
            margin: 10px 0;
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
                    <td style="padding-right: 5px;">{{ $item->nama_barang }}</td>
                    <td class="text-right" style="white-space: nowrap;">{{ (int)$item->jumlah }}</td>
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
                <td>Diskon Trans:</td>
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
            <td style="padding-top: 5px;">Kembali:</td>
            <td class="text-right" style="padding-top: 5px;">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</td>
        </tr>
    </table>

    <hr>

    <div class="footer">
        <p><strong>Metode: {{ strtoupper($transaksi->metode_pembayaran) }}</strong></p>
        <p>Terima kasih telah berbelanja</p>
        <p>*** Barang yang sudah dibeli tidak dapat ditukar ***</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; margin: 5px; cursor: pointer;">🖨️ Print</button>
        <button onclick="window.close()" style="padding: 10px 20px; margin: 5px; cursor: pointer;">❌ Tutup</button>
    </div>

    <script>
        window.onload = function() {
            @if (request()->has('autoprint'))
                setTimeout(function() {
                    window.print();
                }, 500);
            @endif
        };
    </script>
</body>

</html>
