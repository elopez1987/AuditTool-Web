<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_stock_wms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_head_id');
            $table->foreignId('inventory_location_id');
            $table->foreignId('inventorypartnumber_id');
            $table->integer('quantity_location');
            $table->integer('quantity_lpns');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_stock_wms');
    }
};
