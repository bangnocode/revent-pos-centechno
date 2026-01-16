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
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_faktur', 50)->unique(); // e.g., INV/SUP/20231201/001
            $table->dateTime('tanggal');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict');
            $table->decimal('total_harga', 15, 2);
            $table->enum('status', ['draft', 'selesai', 'dibatalkan'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users'); // Operator who created it
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
