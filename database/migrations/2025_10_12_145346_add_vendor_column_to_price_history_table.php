<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('price_history', function (Blueprint $table) {
            // Agregar columna si no existe
            if (!Schema::hasColumn('price_history', 'vendor')) {
                $table->string('vendor')->after('component_id');
            }
        });

        // Agregar índice si no existe
        $indexExists = DB::select("
            SHOW INDEX FROM price_history WHERE Key_name = 'idx_vendor'
        ");

        if (empty($indexExists)) {
            Schema::table('price_history', function (Blueprint $table) {
                $table->index('vendor', 'idx_vendor');
            });
        }
    }

    public function down(): void
    {
        // Eliminar índice si existe
        $indexExists = DB::select("
            SHOW INDEX FROM price_history WHERE Key_name = 'idx_vendor'
        ");

        if (!empty($indexExists)) {
            Schema::table('price_history', function (Blueprint $table) {
                $table->dropIndex('idx_vendor');
            });
        }

        // Eliminar columna si existe
        if (Schema::hasColumn('price_history', 'vendor')) {
            Schema::table('price_history', function (Blueprint $table) {
                $table->dropColumn('vendor');
            });
        }
    }
};