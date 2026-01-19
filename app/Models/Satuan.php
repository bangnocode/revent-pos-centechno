<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_satuan',
        'singkatan',
        'status_aktif'
    ];

    protected $casts = [
        'status_aktif' => 'boolean'
    ];

    // Relasi dengan Barang
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'satuan_id');
    }

    // Scope untuk satuan aktif
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }
}
