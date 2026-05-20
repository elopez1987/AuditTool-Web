<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventorypartnumberdata01', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventorypartnumber_id');
            $table->string('container_type')->nullable();
            $table->string('pn_turbo')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('machines_component')->nullable();
            $table->timestamps();
            $table->index(['inventorypartnumber_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventorypartnumberdata01');
    }
};
