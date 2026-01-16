<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menggunakan raw statement karena mengubah ENUM via Schema Builder sering bermasalah di beberapa driver
        // Asumsi menggunakan MySQL/MariaDB (XAMPP default)
        DB::statement("ALTER TABLE transaksi_penjualan MODIFY COLUMN metode_pembayaran ENUM('tunai', 'debit', 'kredit', 'transfer', 'qris', 'hutang') DEFAULT 'tunai'");
        
        // Menambah opsi 'hutang' di status_pembayaran agar lebih eksplisit (opsional, tapi diminta user 'mana yang hutang')
        DB::statement("ALTER TABLE transaksi_penjualan MODIFY COLUMN status_pembayaran ENUM('lunas', 'pending', 'kredit', 'hutang') DEFAULT 'lunas'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke definisi semula
        DB::statement("ALTER TABLE transaksi_penjualan MODIFY COLUMN metode_pembayaran ENUM('tunai', 'debit', 'kredit', 'transfer', 'qris') DEFAULT 'tunai'");
        DB::statement("ALTER TABLE transaksi_penjualan MODIFY COLUMN status_pembayaran ENUM('lunas', 'pending', 'kredit') DEFAULT 'lunas'");
    }
};
