<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alats', function (Blueprint $table) {
            $table->dropColumn(['kondisi', 'lokasi','deskripsi']);
        });
    }

    public function down(): void
    {
        Schema::table('alats', function (Blueprint $table) {
            $table->enum('kondisi', ['baik', 'sedang', 'rusak'])->nullable();
            $table->string('lokasi')->nullable();
            $table->text('deskripsi')->nullable();
        });
    }
};
