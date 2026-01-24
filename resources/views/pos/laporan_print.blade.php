<!DOCTYPE html>
<html>

<head>
    <title>Laporan Keuangan Kasir - {{ $summary['tanggal'] }}</title>
    <style>
        @media print {
            body {
                font-family: 'Courier New', monospace;
                width: 55mm;
                margin: 0 auto;
                padding: 5px;
                font-size: 11px;
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

        .font-bold {
            font-weight: bold;
        }
        
        hr {
            border-top: 1px dashed #ccc;
            border-bottom: none;
            margin: 10px 0;
        }

        .mb-2 {
            margin-bottom: -7px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3 class="mb-2">LAPORAN KASIR</h3>
        <p class="mb-2"><strong>TOKO CT</strong></p>
        <hr>
    </div>

    <div class="content">
        <p class="mb-2">Kasir: {{ $summary['kasir'] }}</p>
        <p class="mb-2">Tanggal: {{ $summary['tanggal'] }}</p>
        <hr>

        <table>
            @foreach ($summary['per_metode'] as $metode => $total)
                <tr>
                    <td>{{ $metode === 'hutang' ? 'Sisa Hutang:' : 'Jual ' . strtoupper($metode) . ':' }}</td>
                    <td class="text-right">{{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            @if(isset($summary['kontan_hutang']) && $summary['kontan_hutang'] > 0)
                <tr>
                    <td>DP Hutang:</td>
                    <td class="text-right">{{ number_format($summary['kontan_hutang'], 0, ',', '.') }}</td>
                </tr>
            @endif
        </table>

        <hr>

        <table>
            <tr>
                <td>Jumlah Nota:</td>
                <td class="text-right">{{ $summary['jumlah_transaksi'] }}</td>
            </tr>
            <tr class="font-bold">
                <td>JUMLAH SETOR:</td>
                <td class="text-right">Rp {{ number_format($summary['total_semua'], 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <hr>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
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
                    window.onafterprint = function() {
                        window.close();
                    };
                    setTimeout(function() {
                        window.close();
                    }, 2000);
                }, 500);
            @endif
        };
    </script>
</body>

</html>
