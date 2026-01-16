<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'kode_pelanggan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'kode_pelanggan',
        'nama_pelanggan',
        'tipe_pelanggan',
        'nomor_telepon',
        'alamat',
        'email',
        'poin_sekarang',
        'total_belanja',
        'tanggal_bergabung',
        'status_aktif',
        'keterangan'
    ];
}