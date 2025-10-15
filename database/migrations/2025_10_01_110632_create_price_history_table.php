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
        Schema::create('price_history', function (Blueprint $table) {
            $table->id();
            $table->morphs('component');
            $table->string('vendor');
            $table->float('price');
            $table->timestamps();

            $table->index(['component_type', 'component_id'], 'idx_component');
            $table->index('created_at', 'idx_date');
            $table->index('vendor', 'idx_vendor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_history');
    }
};

