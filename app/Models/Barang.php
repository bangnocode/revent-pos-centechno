<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'kode_barang';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_barang',
        'barcode',
        'nama_barang',
        'kategori',
        'merek',
        'satuan',
        'stok_sekarang',
        'stok_minimum',
        'harga_beli_terakhir',
        'harga_jual_normal',
        'harga_jual_grosir',
        'diskon_maksimum',
        'supplier_utama',
        'tanggal_kadaluarsa',
        'status_aktif'
    ];
}