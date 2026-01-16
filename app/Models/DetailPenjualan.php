<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'nomor_faktur',
        'kode_barang',
        'nama_barang',
        'jumlah',
        'satuan',
        'harga_satuan',
        'diskon_item',
        'subtotal_item',
        'harga_beli_saat_itu',
        'margin'
    ];

    // Relasi ke transaksi
    public function transaksi()
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'nomor_faktur', 'nomor_faktur');
    }

    // Relasi ke barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }
}