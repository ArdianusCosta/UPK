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
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_hp')->nullable()->after('remember_token');
            $table->string('bio_singkat_ajasih')->nullable()->after('no_hp');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->after('bio_singkat_ajasih');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif')->after('jenis_kelamin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('no_hp');
            $table->dropColumn('bio_singkat_ajasih');
            $table->dropColumn('jenis_kelamin');
            $table->dropColumn('status');
        });
    }
};
