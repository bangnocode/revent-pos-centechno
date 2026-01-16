<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pembelian;
use App\Models\Barang;

class DetailPembelian extends Model
{
    protected $fillable = [
        'pembelian_id',
        'kode_barang',
        'jumlah',
        'harga_beli_satuan',
        'subtotal',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode_barang');
    }
}
