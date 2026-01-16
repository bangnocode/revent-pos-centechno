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
        Schema::create('operator', function (Blueprint $table) {
            $table->string('id_operator', 10)->primary();
            $table->string('nama_lengkap', 50);
            $table->string('username', 20);
            $table->string('password_hash', 255);
            $table->enum('level_akses', ['admin', 'kasir', 'supervisor', 'owner'])->default('kasir');
            $table->string('bagian', 30)->nullable();
            $table->string('kode_toko', 10)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamp('tanggal_dibuat')->useCurrent();
            $table->dateTime('terakhir_login')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operator');
    }
};
