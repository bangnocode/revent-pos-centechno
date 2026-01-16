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
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->string('kode_pelanggan', 20)->primary();
            $table->string('nama_pelanggan', 100);
            $table->enum('tipe_pelanggan', ['retail', 'grosir', 'member', 'corporate'])->default('retail');
            $table->string('nomor_telepon', 20)->nullable();
            $table->string('alamat', 200)->nullable();
            $table->string('email', 100)->nullable();
            $table->decimal('poin_sekarang', 24, 2)->default(0);
            $table->decimal('total_belanja', 24, 2)->default(0);
            $table->date('tanggal_bergabung')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->text('keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
