<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_aisles', function (Blueprint $table) {
            $table->id();
            $table->string('centerid');
            $table->string('warehouseid');
            $table->string('name');
            $table->string('configurationflag')->nullable();
            $table->string('auxiliary01')->nullable();
            $table->string('auxiliary02')->nullable();
            $table->string('auxiliary03')->nullable();
            $table->string('schemaid');
            $table->string('statusid');
            $table->timestamps();
            $table->index(['centerid']);
            $table->index(['warehouseid']);
            $table->index(['name']);
            $table->index(['statusid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_aisles');
    }
};
