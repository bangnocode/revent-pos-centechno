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
        Schema::create('detail_pembelians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelians')->onDelete('cascade');
            // Assuming 'barang' table uses 'kode_barang' string PK, not id.
            // verifying: 2025_12_06_132749_create_barang_table.php -> $table->string('kode_barang', 20)->primary();
            $table->string('kode_barang', 20);
            $table->foreign('kode_barang')->references('kode_barang')->on('barang')->onDelete('restrict');
            
            $table->decimal('jumlah', 10, 2); // Qty purchased
            $table->decimal('harga_beli_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembelians');
    }
};
