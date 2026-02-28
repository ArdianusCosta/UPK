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
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique()->nullable();
            $table->foreignId('peminjam_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('petugas_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('alat_id')->constrained('alats')->onDelete('cascade');
            $table->date('tanggal_pinjam')->nullable();
            $table->enum('status', ['Pending', 'Dipinjam', 'Ditolak', 'Terlambat', 'Dikembalikan'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
