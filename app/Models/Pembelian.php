<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $fillable = [
        'nomor_faktur',
        'tanggal',
        'supplier_id',
        'total_harga',
        'status',
        'metode_pembayaran',
        'keterangan',
        'user_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function details()
    {
        return $this->hasMany(DetailPembelian::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
