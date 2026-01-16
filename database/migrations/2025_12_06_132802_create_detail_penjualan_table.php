<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->string('nomor_faktur', 25);
            $table->string('kode_barang', 20);
            $table->string('nama_barang', 100);
            $table->decimal('jumlah', 14, 2)->default(1);
            $table->string('satuan', 20);
            $table->decimal('harga_satuan', 24, 2);
            $table->decimal('diskon_item', 24, 2)->default(0);
            $table->decimal('subtotal_item', 24, 2);
            $table->decimal('harga_beli_saat_itu', 24, 2);
            $table->decimal('margin', 24, 2);

            $table->index('nomor_faktur', 'idx_faktur');
            $table->index('kode_barang', 'idx_barang');

            $table->foreign('nomor_faktur')->references('nomor_faktur')->on('transaksi_penjualan')->onDelete('cascade');
            $table->foreign('kode_barang')->references('kode_barang')->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
