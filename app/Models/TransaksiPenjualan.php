<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_penjualan';
    protected $primaryKey = 'nomor_faktur';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nomor_faktur',
        'tanggal_transaksi',
        'kode_pelanggan',
        'nama_pelanggan',
        'jumlah_item',
        'subtotal',
        'diskon_transaksi',
        'pajak_ppn',
        'biaya_tambahan',
        'total_transaksi',
        'total_bayar',
        'kembalian',
        'metode_pembayaran',
        'status_pembayaran',
        'id_operator',
        'kode_toko',
        'keterangan',
        'is_canceled'
    ];

    // Relasi ke detail penjualan
    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'nomor_faktur', 'nomor_faktur');
    }
}