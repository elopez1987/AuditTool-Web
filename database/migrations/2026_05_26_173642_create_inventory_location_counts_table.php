<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_location_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_head_id')->constrained('inventory_heads')->onDelete('cascade');
            $table->foreignId('inventory_aisle_id');
            $table->foreignId('inventory_card_id');
            $table->foreignId('inventory_location_id');
            $table->foreignId('inventorypartnumber01_id')->nullable();
            $table->integer('quantity01')->nullable();
            $table->foreignId('inventorypartnumber02_id')->nullable();
            $table->integer('quantity02')->nullable();
            $table->foreignId('user_count01_id')->nullable();
            $table->foreignId('user_count02_id')->nullable();
            $table->string('statusid_count01', 10)->nullable();
            $table->string('statusid_count02', 10)->nullable();
            $table->string('statusid_location', 10)->nullable();
            $table->timestamps();

            $table->index(['inventory_head_id']);
            $table->index(['inventory_aisle_id']);
            $table->index(['inventory_card_id']);
            $table->index(['inventory_location_id']);
            $table->index(['inventorypartnumber01_id']);
            $table->index(['inventorypartnumber02_id']);
            $table->index(['user_count01_id']);
            $table->index(['user_count02_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_location_counts');
    }
};
