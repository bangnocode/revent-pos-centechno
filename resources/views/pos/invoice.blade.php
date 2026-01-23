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

        .mb-8 {
            margin-bottom: -8px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3 class="mb-8">TOKO CT</h3>
        <p class="mb-8">Jl. Contoh No. 123</p>
        <p class="mb-8">Telp: 0812-3456-7890</p>
        <hr class="mb-8">
        <p class="mb-8"><strong>{{ $transaksi->nomor_faktur }}</strong></p>
        <p class="mb-8">Tanggal: {{ date('d/m/Y H:i', strtotime($transaksi->tanggal_transaksi)) }}</p>
        <p class="mb-8">Kasir: {{ $transaksi->id_operator }}</p>
        <p class="mb-8">Pelanggan: {{ $transaksi->nama_pelanggan }}</p>
    </div>

    <hr>

    <table>
        <thead>
            <tr>
                <td><strong>Barang</strong></td>
                <td class="text-right"><strong>Subtotal</strong></td>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->details as $item)
                <tr>
                    <td style="padding-right: 5px;">{{ $item->nama_barang }} <br> {{ (int)$item->jumlah }} x {{ (int)$item->harga_satuan}}</td>
                    <td class="text-right">{{ number_format($item->subtotal_item, 0, ',', '.') }} 
                        @if ($item->diskon_item > 0)
                        <br>
                        - {{ number_format($item->diskon_item, 0, ',', '.') }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <table>
        @php
            $totalDiskonItem = $transaksi->details->sum('diskon_item');
            $subtotal = $transaksi->subtotal;
            $subtotalNota = $subtotal - $totalDiskonItem;
        @endphp

        <tr>
            <td>Subtotal:</td>
            <td class="text-right">Rp {{ number_format($subtotalNota, 0, ',', '.') }}</td>
        </tr>

        @if ($transaksi->diskon_transaksi > 0)
            <tr>
                <td>Diskon Tambahan:</td>
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
        <p class="mb-8"><strong>Metode: {{ strtoupper($transaksi->metode_pembayaran) }}</strong></p>
        <p class="mb-8">Terima kasih telah berbelanja</p>
        <p class="mb-8">*** Barang yang sudah dibeli tidak dapat ditukar ***</p>
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
                    
                    // onafterprint will fire after the print dialog is closed (printed or cancelled)
                    window.onafterprint = function() {
                        window.close();
                    };

                    // Fallback for browsers that might not support onafterprint properly
                    setTimeout(function() {
                        window.close();
                    }, 2000);
                }, 500);
            @endif
        };
    </script>
</body>

</html>
