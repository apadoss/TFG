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
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('cpu_id')->references('id')->on('cpus');
            $table->bigInteger('gpu_id')->references('id')->on('graphic_cards');
            $table->bigInteger('storage_id')->references('id')->on('storage_devices');
            $table->bigInteger('ram_id')->references('id')->on('rams');
            $table->bigInteger('power_supply_id')->references('id')->on('power_supplies');
            $table->bigInteger('motherboard_id')->references('id')->on('motherboards');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
