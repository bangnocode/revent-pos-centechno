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
        Schema::create('barang', function (Blueprint $table) {
            $table->string('kode_barang', 20)->primary();
            $table->string('barcode', 20)->unique()->nullable();
            $table->string('nama_barang', 100);
            $table->string('kategori', 30)->nullable();
            $table->string('merek', 30)->nullable();
            $table->string('satuan', 20);
            $table->decimal('stok_sekarang', 14, 2)->default(0);
            $table->decimal('stok_minimum', 14, 2)->default(0);
            $table->decimal('harga_beli_terakhir', 24, 2)->nullable();
            $table->decimal('harga_jual_normal', 24, 2);
            $table->decimal('harga_jual_grosir', 24, 2)->nullable();
            $table->decimal('diskon_maksimum', 5, 2)->default(0);
            $table->string('supplier_utama', 30)->nullable();
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
