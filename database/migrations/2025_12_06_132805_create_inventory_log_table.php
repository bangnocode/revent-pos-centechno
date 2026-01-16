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
        Schema::create('inventory_log', function (Blueprint $table) {
            $table->id('id_log');
            $table->dateTime('tanggal_log')->useCurrent();
            $table->string('kode_barang', 20);
            $table->enum('jenis_pergerakan', ['pembelian', 'penjualan', 'retur_jual', 'retur_beli', 'adjustment', 'opname']);
            $table->decimal('jumlah_pergerakan', 14, 2);
            $table->decimal('stok_sebelum', 14, 2);
            $table->decimal('stok_sesudah', 14, 2);
            $table->string('nomor_referensi', 25)->nullable();
            $table->string('id_operator', 10);
            $table->string('keterangan', 200)->nullable();

            $table->index('tanggal_log', 'idx_tanggal');
            $table->index('kode_barang', 'idx_barang');
            $table->index('jenis_pergerakan', 'idx_jenis');

            $table->foreign('kode_barang')->references('kode_barang')->on('barang');
            $table->foreign('id_operator')->references('id_operator')->on('operator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_log');
    }
};
