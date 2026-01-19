<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\TransaksiPenjualan;
use App\Models\DetailPenjualan;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{

    public function printInvoice($nomor_faktur)
    {
        // Ganti 'items' dengan 'details' sesuai model yang ada
        $transaksi = TransaksiPenjualan::with('details')->where('nomor_faktur', $nomor_faktur)->first();

        if (!$transaksi) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        return view('pos.invoice', compact('transaksi'));
    }

    public function printInvoiceData($nomor_faktur)
    {
        $transaksi = TransaksiPenjualan::with('details')->where('nomor_faktur', $nomor_faktur)->first();

        if (!$transaksi) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json($transaksi);
    }

    /**
     * Tampilkan halaman utama POS
     */
    public function index()
    {
        return view('pos.index');
    }

    /**
     * Cari barang berdasarkan barcode/kode/nama
     */
    public function cariBarang(Request $request)
    {
        $keyword = $request->input('keyword');

        $barang = Barang::where('status_aktif', true)
            ->where(function ($query) use ($keyword) {
                $query->where('barcode', $keyword)
                    ->orWhere('kode_barang', $keyword)
                    ->orWhere('nama_barang', 'like', "%$keyword%");
            })
            ->first();

        if ($barang) {
            return response()->json([
                'success' => true,
                'data' => $barang
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Barang tidak ditemukan'
        ]);
    }

    /**
     * Simpan transaksi baru dengan support diskon
     */
    public function simpanTransaksi(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'nama_pelanggan' => 'nullable|string|max:100',
            'metode_pembayaran' => 'required|in:tunai,debit,kredit,transfer,qris,hutang',
            'total_bayar' => 'required|numeric|min:0',
            'total_transaksi' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0', // Subtotal sebelum diskon
            'diskon_transaksi' => 'nullable|numeric|min:0', // Diskon global
        ]);

        // Generate nomor faktur
        $nomorFaktur = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        // Hitung kembalian / sisa hutang
        // Jika kembalian negatif artinya hutang (kurang bayar)
        $kembalian = $request->total_bayar - $request->total_transaksi;

        // Validasi pembayaran
        if ($request->metode_pembayaran !== 'hutang') {
            // Jika BUKAN hutang, harus lunas (uang pas atau lebih)
            if ($kembalian < -0.01) { // Toleransi floating point
                return response()->json([
                    'success' => false,
                    'message' => 'Uang dibayar kurang'
                ], 400);
            }
            $statusPembayaran = 'lunas';
        } else {
            // Jika HUTANG, statusnya 'hutang' (meskipun dibayar lunas, user minta dicatat sbg hutang di awal, 
            // tapi biasanya kalau lunas ya lunas. Namun sesuai request "mana yang hutang", 
            // kita set status 'hutang' jika belum lunas, atau jika metode 'hutang' dipilih.)

            // Logic: Jika bayar full meski pilih hutang -> Lunas? Atau tetap Hutang history?
            // Biasanya pilih hutang = niat hutang.
            // Kita set status sesuai sisa pembayaran.
            if ($kembalian >= 0) {
                $statusPembayaran = 'lunas'; // Tulis lunas jika ternyata dibayar full
            } else {
                $statusPembayaran = 'hutang';
            }
        }

        // Validasi diskon tidak lebih dari subtotal
        $diskonTransaksi = floatval($request->diskon_transaksi ?? 0);
        if ($diskonTransaksi > $request->subtotal) {
            return response()->json([
                'success' => false,
                'message' => 'Diskon tidak boleh lebih dari subtotal'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Buat transaksi dengan diskon
            $transaksi = TransaksiPenjualan::create([
                'nomor_faktur' => $nomorFaktur,
                'tanggal_transaksi' => now(), // Jangan lupa tanggal
                'nama_pelanggan' => $request->nama_pelanggan ?? 'Pelanggan Umum',
                'jumlah_item' => count($request->items),
                'subtotal' => $request->subtotal, // Subtotal sebelum diskon
                'diskon_transaksi' => $diskonTransaksi, // Diskon global
                'total_transaksi' => $request->total_transaksi, // Total setelah diskon
                'total_bayar' => $request->total_bayar,
                'kembalian' => $kembalian,
                'metode_pembayaran' => $request->metode_pembayaran,
                'id_operator' => auth()->user()->username ?? 'admin', // Default for development
                'status_pembayaran' => $statusPembayaran,
            ]);

            // Hitung total diskon item untuk validasi
            $totalDiskonItem = 0;

            // Simpan detail transaksi
            foreach ($request->items as $item) {
                $barang = Barang::where('kode_barang', $item['kode_barang'])->first();

                if ($barang) {
                    // Konversi ke float untuk memastikan
                    $hargaSatuan = floatval($item['harga_satuan']);
                    $jumlah = floatval($item['jumlah']);
                    $diskonItem = floatval($item['diskon_item'] ?? 0);
                    $subtotalItem = floatval($item['subtotal']);
                    $hargaBeli = floatval($barang->harga_beli_terakhir ?? 0);

                    // Validasi diskon item tidak lebih dari harga * qty
                    $maxDiskonItem = $hargaSatuan * $jumlah;
                    if ($diskonItem > $maxDiskonItem) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Diskon item {$item['nama_barang']} melebihi harga"
                        ], 400);
                    }

                    $totalDiskonItem += $diskonItem;

                    // Simpan detail dengan diskon item
                    DetailPenjualan::create([
                        'nomor_faktur' => $nomorFaktur,
                        'kode_barang' => $item['kode_barang'],
                        'nama_barang' => $item['nama_barang'],
                        'jumlah' => $jumlah,
                        'satuan' => $item['satuan'],
                        'harga_satuan' => $hargaSatuan,
                        'diskon_item' => $diskonItem, // Diskon per item
                        'subtotal_item' => $subtotalItem, // Sudah termasuk diskon item
                        'harga_beli_saat_itu' => $hargaBeli,
                        'margin' => ($hargaSatuan - $hargaBeli) * $jumlah,
                    ]);

                    // Update stok
                    $stokSebelum = $barang->stok_sekarang;
                    $barang->decrement('stok_sekarang', $jumlah);
                    $barang->tgl_stok_keluar = now()->toDateString(); // Set tgl_stok_keluar
                    $barang->save(); // Save the changes
                    $stokSesudah = $barang->stok_sekarang;

                    // Log inventory
                    InventoryLog::create([
                        'kode_barang' => $item['kode_barang'],
                        'jenis_pergerakan' => 'penjualan',
                        'jumlah_pergerakan' => -$jumlah,
                        'stok_sebelum' => $stokSebelum,
                        'stok_sesudah' => $stokSesudah,
                        'nomor_referensi' => $nomorFaktur,
                        'id_operator' => auth()->user()->username ?? 'admin',
                        'keterangan' => 'Penjualan POS',
                    ]);
                } else {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Barang {$item['kode_barang']} tidak ditemukan"
                    ], 404);
                }
            }

            // Validasi total diskon (item + global) tidak lebih dari subtotal
            $totalSemuaDiskon = $totalDiskonItem + $diskonTransaksi;
            if ($totalSemuaDiskon > $request->subtotal) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Total diskon melebihi subtotal'
                ], 400);
            }

            // Validasi consistency: subtotal - total diskon = total_transaksi
            $calculatedTotal = $request->subtotal - $totalSemuaDiskon;
            if (abs($calculatedTotal - $request->total_transaksi) > 0.01) { // Allow small rounding differences
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Perhitungan total tidak sesuai'
                ], 400);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'nomor_faktur' => $nomorFaktur,
                'kembalian' => $kembalian,
                'total_diskon_item' => $totalDiskonItem,
                'diskon_transaksi' => $diskonTransaksi,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error simpan transaksi: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
