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
        Schema::create('pending_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('component_type');
            $table->unsignedBigInteger('component_id');
            $table->string('vendor');
            $table->decimal('old_price', 10, 2);
            $table->decimal('new_price', 10, 2);
            $table->boolean('processed')->default(false);
            $table->timestamps();

            $table->index(['component_type', 'component_id'], 'idx_pending_component');
            $table->unique(['component_type', 'component_id', 'vendor'], 'unique_pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_notifications');
    }
};
