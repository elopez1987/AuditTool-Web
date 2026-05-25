<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_locations', function (Blueprint $table) {
            $table->id();
            $table->string('centerid', 15);
            $table->string('warehouseid', 15);
            $table->foreignId('inventory_card_id');
            $table->string('name');
            $table->string('configurationflag')->nullable();
            $table->foreignId('inventoryunitsmeasure_id');
            $table->string('auxiliary01')->nullable();
            $table->string('auxiliary02')->nullable();
            $table->string('auxiliary03')->nullable();
            $table->string('schemaid', 10);
            $table->string('statusid', 10);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['centerid']);
            $table->index(['warehouseid']);
            $table->index(['name']);
            $table->index(['inventoryunitsmeasure_id']);
            $table->index(['statusid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_locations');
    }
};
