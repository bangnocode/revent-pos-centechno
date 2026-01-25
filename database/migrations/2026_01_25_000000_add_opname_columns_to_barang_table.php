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
        Schema::table('barang', function (Blueprint $row) {
            $row->integer('selisih_stok')->default(0)->after('stok_sekarang');
            $row->timestamp('tanggal_cek_stok')->nullable()->after('selisih_stok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $row) {
            $row->dropColumn(['selisih_stok', 'tanggal_cek_stok']);
        });
    }
};
