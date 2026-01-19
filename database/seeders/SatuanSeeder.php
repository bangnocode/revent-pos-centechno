<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $satuans = [
            ['nama_satuan' => 'Pieces', 'singkatan' => 'pcs', 'status_aktif' => true],
            ['nama_satuan' => 'Box', 'singkatan' => 'box', 'status_aktif' => true],
            ['nama_satuan' => 'Pack', 'singkatan' => 'pack', 'status_aktif' => true],
            ['nama_satuan' => 'Kilogram', 'singkatan' => 'kg', 'status_aktif' => true],
            ['nama_satuan' => 'Gram', 'singkatan' => 'gr', 'status_aktif' => true],
            ['nama_satuan' => 'Liter', 'singkatan' => 'L', 'status_aktif' => true],
            ['nama_satuan' => 'Mililiter', 'singkatan' => 'ml', 'status_aktif' => true],
            ['nama_satuan' => 'Buah', 'singkatan' => 'buah', 'status_aktif' => true],
            ['nama_satuan' => 'Botol', 'singkatan' => 'btl', 'status_aktif' => true],
            ['nama_satuan' => 'Karton', 'singkatan' => 'krt', 'status_aktif' => true],
        ];

        foreach ($satuans as $satuan) {
            Satuan::create($satuan);
        }
    }
}
