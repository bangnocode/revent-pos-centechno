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
        Schema::create('rekenings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rekening')->unique();
            $table->string('nama_rekening');
            $table->enum('tipe_rekening', ['induk', 'transaksi']); // induk (Grandparent/Parent), transaksi (Children)
            $table->enum('posisi_rekening', ['A', 'P']); // Aktiva, Pasiva
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekenings');
    }
};
