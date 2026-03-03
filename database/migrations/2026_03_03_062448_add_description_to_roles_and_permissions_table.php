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
        $tableNames = config('permission.table_names');
        
        if (Schema::hasTable($tableNames['roles'])) {
            Schema::table($tableNames['roles'], function (Blueprint $table) use ($tableNames) {
                if (!Schema::hasColumn($tableNames['roles'], 'description')) {
                    $table->text('description')->nullable()->after('name');
                }
            });
        }

        if (Schema::hasTable($tableNames['permissions'])) {
            Schema::table($tableNames['permissions'], function (Blueprint $table) use ($tableNames) {
                if (!Schema::hasColumn($tableNames['permissions'], 'description')) {
                    $table->text('description')->nullable()->after('name');
                }
            });
        }
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');

        if (Schema::hasTable($tableNames['roles'])) {
            Schema::table($tableNames['roles'], function (Blueprint $table) use ($tableNames) {
                if (Schema::hasColumn($tableNames['roles'], 'description')) {
                    $table->dropColumn('description');
                }
            });
        }

        if (Schema::hasTable($tableNames['permissions'])) {
            Schema::table($tableNames['permissions'], function (Blueprint $table) use ($tableNames) {
                if (Schema::hasColumn($tableNames['permissions'], 'description')) {
                    $table->dropColumn('description');
                }
            });
        }
    }
};
