<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_card_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_head_id');
            $table->foreignId('inventory_aisle_id');
            $table->foreignId('inventory_card_id');

            $table->index(['inventory_head_id']);
            $table->index(['inventory_aisle_id']);
            $table->index(['inventory_card_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_card_counts');
    }
};
