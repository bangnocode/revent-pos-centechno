<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Operator;
use App\Models\Barang;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Operator dummy
        Operator::create([
            'id_operator' => 'OP001',
            'nama_lengkap' => 'Admin Kasir',
            'username' => 'admin',
            'password_hash' => Hash::make('password123'),
            'level_akses' => 'kasir',
            'status_aktif' => true,
        ]);

        // Barang dummy
        $barangData = [
            [
                'kode_barang' => 'BRG001',
                'barcode' => '899999901001',
                'nama_barang' => 'Aqua Botol 600ml',
                'kategori' => 'Minuman',
                'satuan' => 'botol',
                'stok_sekarang' => 100,
                'harga_jual_normal' => 3000,
                'harga_beli_terakhir' => 2000,
                'status_aktif' => true,
            ],
            [
                'kode_barang' => 'BRG002',
                'barcode' => '899999901002',
                'nama_barang' => 'Indomie Goreng',
                'kategori' => 'Makanan',
                'satuan' => 'pcs',
                'stok_sekarang' => 50,
                'harga_jual_normal' => 2500,
                'harga_beli_terakhir' => 1800,
                'status_aktif' => true,
            ],
            [
                'kode_barang' => 'BRG003',
                'barcode' => '899999901003',
                'nama_barang' => 'Pulpen Standard',
                'kategori' => 'Alat Tulis',
                'satuan' => 'pcs',
                'stok_sekarang' => 200,
                'harga_jual_normal' => 2000,
                'harga_beli_terakhir' => 1200,
                'status_aktif' => true,
            ],
            [
                'kode_barang' => 'BRG004',
                'barcode' => '899999901004',
                'nama_barang' => 'Roti Tawar',
                'kategori' => 'Makanan',
                'satuan' => 'bungkus',
                'stok_sekarang' => 30,
                'harga_jual_normal' => 12000,
                'harga_beli_terakhir' => 8000,
                'status_aktif' => true,
            ],
            [
                'kode_barang' => 'BRG005',
                'barcode' => '899999901005',
                'nama_barang' => 'Sabun Mandi Lifebuoy',
                'kategori' => 'Kebutuhan Mandi',
                'satuan' => 'pcs',
                'stok_sekarang' => 80,
                'harga_jual_normal' => 5000,
                'harga_beli_terakhir' => 3500,
                'status_aktif' => true,
            ],
            [
                'kode_barang' => 'BRG006',
                'barcode' => '899999901006',
                'nama_barang' => 'Teh Botol Sosro 500ml',
                'kategori' => 'Minuman',
                'satuan' => 'botol',
                'stok_sekarang' => 75,
                'harga_jual_normal' => 4500,
                'harga_beli_terakhir' => 3200,
                'status_aktif' => true,
            ],
            [
                'kode_barang' => 'BRG007',
                'barcode' => '899999901007',
                'nama_barang' => 'Mie Sedap Goreng',
                'kategori' => 'Makanan',
                'satuan' => 'pcs',
                'stok_sekarang' => 60,
                'harga_jual_normal' => 2300,
                'harga_beli_terakhir' => 1700,
                'status_aktif' => true,
            ],
            [
                'kode_barang' => 'BRG008',
                'barcode' => '899999901008',
                'nama_barang' => 'Buku Tulis Sidu 38L',
                'kategori' => 'Alat Tulis',
                'satuan' => 'buah',
                'stok_sekarang' => 150,
                'harga_jual_normal' => 3500,
                'harga_beli_terakhir' => 2500,
                'status_aktif' => true,
            ],
            [
                'kode_barang' => 'BRG009',
                'barcode' => '899999901009',
                'nama_barang' => 'Shampoo Clear 180ml',
                'kategori' => 'Kebutuhan Mandi',
                'satuan' => 'botol',
                'stok_sekarang' => 45,
                'harga_jual_normal' => 15000,
                'harga_beli_terakhir' => 11000,
                'status_aktif' => true,
            ],
            [
                'kode_barang' => 'BRG010',
                'barcode' => '899999901010',
                'nama_barang' => 'Tissue Paseo 200 sheet',
                'kategori' => 'Kebutuhan Rumah Tangga',
                'satuan' => 'buah',
                'stok_sekarang' => 120,
                'harga_jual_normal' => 8000,
                'harga_beli_terakhir' => 5500,
                'status_aktif' => true,
            ],
            [
                'kode_barang' => 'BRG011',
                'barcode' => '899999901011',
                'nama_barang' => 'Kopi Kapal Api 100gr',
                'kategori' => 'Minuman',
                'satuan' => 'sachet',
                'stok_sekarang' => 200,
                'harga_jual_normal' => 2000,
                'harga_beli_terakhir' => 1400,
                'status_aktif' => true,
            ],
            [
                'kode_barang' => 'BGR012',
                'barcode' => '899999901012',
                'nama_barang' => 'Biskuit Roma Kelapa',
                'kategori' => 'Makanan',
                'satuan' => 'kaleng',
                'stok_sekarang' => 25,
                'harga_jual_normal' => 18000,
                'harga_beli_terakhir' => 12500,
                'status_aktif' => true,
            ],
            [
                'kode_barang' => 'BRG013',
                'barcode' => '899999901013',
                'nama_barang' => 'Penggaris 30cm',
                'kategori' => 'Alat Tulis',
                'satuan' => 'pcs',
                'stok_sekarang' => 80,
                'harga_jual_normal' => 3000,
                'harga_beli_terakhir' => 1800,
                'status_aktif' => true,
            ],
            [
                'kode_barang' => 'BRG014',
                'barcode' => '899999901014',
                'nama_barang' => 'Sikat Gigi Formula',
                'kategori' => 'Kesehatan',
                'satuan' => 'pcs',
                'stok_sekarang' => 90,
                'harga_jual_normal' => 4000,
                'harga_beli_terakhir' => 2500,
                'status_aktif' => true,
            ],
            [
                'kode_barang' => 'BRG015',
                'barcode' => '899999901015',
                'nama_barang' => 'Gula Pasir 1kg',
                'kategori' => 'Sembako',
                'satuan' => 'karung',
                'stok_sekarang' => 40,
                'harga_jual_normal' => 14000,
                'harga_beli_terakhir' => 10000,
                'status_aktif' => true,
            ],
        ];

        foreach ($barangData as $barang) {
            Barang::create($barang);
        }

        // Pelanggan dummy
        Pelanggan::create([
            'kode_pelanggan' => 'CUST001',
            'nama_pelanggan' => 'Pelanggan Umum',
            'tipe_pelanggan' => 'retail',
            'status_aktif' => true,
        ]);

        $this->command->info('Data dummy berhasil ditambahkan!');
        $this->command->info('Login dengan:');
        $this->command->info('Username: admin');
        $this->command->info('Password: password123');
    }
}
