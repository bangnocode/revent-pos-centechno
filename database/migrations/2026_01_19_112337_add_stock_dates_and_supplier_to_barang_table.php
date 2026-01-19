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
        Schema::table('barang', function (Blueprint $table) {
            $table->date('tgl_stok_masuk')->nullable();
            $table->date('tgl_stok_keluar')->nullable();
            $table->string('nama_supplier', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['tgl_stok_masuk', 'tgl_stok_keluar', 'nama_supplier']);
        });
    }
};
