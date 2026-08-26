<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_assign_counter_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_head_id');
            $table->foreignId('inventory_card_count_id');
            $table->foreignId('user_id')->nullable();
            $table->string('statusid', 15);
            $table->timestamps();

            $table->index('inventory_head_id');
            $table->index('inventory_card_count_id');
            $table->index('user_id');
            $table->index('statusid');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_assign_counter_cards');
    }
};
