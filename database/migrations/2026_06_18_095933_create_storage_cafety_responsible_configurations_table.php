<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storage_safety_responsible_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('storage_safety_responsible_area_id');
            $table->string('level01');
            $table->integer('timeout01');
            $table->integer('attempts01');
            $table->string('level02');
            $table->integer('timeout02');
            $table->integer('attempts02');
            $table->string('level03')->nullable();
            $table->integer('timeout03')->nullable();
            $table->integer('attempts03')->nullable();

            $table->index('storage_safety_responsible_area_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_safety_responsible_configurations');
    }
};
