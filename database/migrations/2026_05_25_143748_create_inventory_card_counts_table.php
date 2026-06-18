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
            $table->foreignId('inventory_head_id')->constrained('inventory_heads')->onDelete('cascade');
            $table->foreignId('inventory_aisle_id');
            $table->foreignId('inventory_card_id');

            $table->foreignId('user_count01_id')->nullable();
            $table->foreignId('user_count02_id')->nullable();
            $table->string('key_user_count_01')->nullable();
            $table->string('key_user_count_02')->nullable();
            $table->string('statusid_count01')->nullable();
            $table->string('statusid_count02')->nullable();

            $table->timestamps();

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
