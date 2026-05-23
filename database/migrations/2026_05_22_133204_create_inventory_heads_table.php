<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_heads', function (Blueprint $table) {
            $table->id();
            $table->uuid('inventory_uuid')->unique();
            $table->string('centerid');
            $table->string('warehouseid');
            $table->string('name');
            $table->foreignId('created_id');
            $table->dateTime('startdate')->nullable();
            $table->dateTime('finishdate')->nullable();
            $table->foreignId('closed_id')->nullable();
            $table->string('schemaid');
            $table->string('statusid');
            $table->timestamps();

            $table->index(['centerid']);
            $table->index(['warehouseid']);
            $table->index(['name']);
            $table->index(['created_id']);
            $table->index(['closed_id']);
            $table->index(['statusid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_heads');
    }
};
