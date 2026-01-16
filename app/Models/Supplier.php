<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'nama_supplier',
        'kontak_person',
        'telepon',
        'alamat',
        'status_aktif',
    ];

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class);
    }
    //
}
