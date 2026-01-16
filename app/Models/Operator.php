<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    protected $table = 'operator';
    protected $primaryKey = 'id_operator';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_operator',
        'nama_lengkap',
        'username',
        'password_hash',
        'level_akses',
        'bagian',
        'kode_toko',
        'status_aktif',
        'tanggal_dibuat',
        'terakhir_login'
    ];
}