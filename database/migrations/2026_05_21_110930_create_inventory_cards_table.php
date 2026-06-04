<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_area_id');
            $table->foreignId('inventory_aisle_id');
            $table->string('name');
            $table->string('configurationflag')->nullable();
            $table->string('auxiliary01')->nullable();
            $table->string('auxiliary02')->nullable();
            $table->string('auxiliary03')->nullable();
            $table->string('schemaid', 10);
            $table->string('statusid', 10);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['name']);
            $table->index(['statusid']);
            $table->index(['inventory_area_id']);
            $table->index(['inventory_aisle_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_cards');
    }
};
