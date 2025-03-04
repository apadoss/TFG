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
        Schema::create('graphic_cards', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("vendor");
            $table->string("brand");
            $table->decimal("price");
            $table->boolean("in_stock");
            $table->string("url");
            $table->string("image");

            $table->string("manufacturer");
            $table->integer("vram");
            $table->string("mem_type");
            $table->integer("tdp");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graphic_cards');
    }
};
