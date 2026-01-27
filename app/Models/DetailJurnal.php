<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailJurnal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }
}
