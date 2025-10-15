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
        Schema::create('power_supplies', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("vendor");
            $table->string("brand");
            $table->decimal("price");
            $table->boolean("in_stock");
            $table->string("url");
            $table->string("image");

            $table->string("certification");
            $table->integer("power");
            $table->timestamps();

            // Clave única para nombre y vendedor
            $table->unique(["name", "vendor"], "unique_power_supply_name_vendor");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('power_supplies');
    }
};
