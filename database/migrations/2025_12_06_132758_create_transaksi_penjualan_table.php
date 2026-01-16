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
        Schema::create('transaksi_penjualan', function (Blueprint $table) {
            $table->string('nomor_faktur', 25)->primary();
            $table->dateTime('tanggal_transaksi')->useCurrent();
            $table->string('kode_pelanggan', 20)->nullable();
            $table->string('nama_pelanggan', 100)->nullable();
            $table->integer('jumlah_item')->default(0);
            $table->decimal('subtotal', 24, 2)->default(0);
            $table->decimal('diskon_transaksi', 24, 2)->default(0);
            $table->decimal('pajak_ppn', 24, 2)->default(0);
            $table->decimal('biaya_tambahan', 24, 2)->default(0);
            $table->decimal('total_transaksi', 24, 2)->default(0);
            $table->decimal('total_bayar', 24, 2)->default(0);
            $table->decimal('kembalian', 24, 2)->default(0);
            $table->enum('metode_pembayaran', ['tunai', 'debit', 'kredit', 'transfer', 'qris'])->default('tunai');
            $table->enum('status_pembayaran', ['lunas', 'pending', 'kredit'])->default('lunas');
            $table->string('id_operator', 10);
            $table->string('kode_toko', 10)->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_canceled')->default(false);

            $table->index('tanggal_transaksi', 'idx_tanggal');
            $table->index('kode_pelanggan', 'idx_pelanggan');
            $table->index('id_operator', 'idx_operator');

            $table->foreign('id_operator')->references('id_operator')->on('operator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_penjualan');
    }
};
