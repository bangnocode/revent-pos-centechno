<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;

    protected $table = 'inventory_log';
    protected $primaryKey = 'id_log';
    public $timestamps = false;

    protected $fillable = [
        'tanggal_log',
        'kode_barang',
        'jenis_pergerakan',
        'jumlah_pergerakan',
        'stok_sebelum',
        'stok_sesudah',
        'nomor_referensi',
        'id_operator',
        'keterangan'
    ];
}