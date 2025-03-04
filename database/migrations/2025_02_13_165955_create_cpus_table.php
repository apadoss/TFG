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
        Schema::create('cpus', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("name");
            $table->string("vendor");
            $table->string("brand");
            $table->decimal("price");
            $table->boolean("in_stock");
            $table->string("url");
            $table->string("image");

            // Atributos especificos cpu
            $table->decimal("clock_speed");
            $table->integer("n_cores");
            $table->integer("n_threads");
            $table->string("socket");
            $table->integer("tdp");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpus');
    }
};
