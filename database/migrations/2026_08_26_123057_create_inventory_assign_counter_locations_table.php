<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_assign_counter_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_assign_counter_card_id');
            $table->foreignId('inventory_location_count_id');
            $table->foreignId('user_id')->nullable();
            $table->string('statusid');
            $table->timestamps();

            $table->index(['inventory_assign_counter_card_id']);
            $table->index(['inventory_location_count_id']);
            $table->index(['statusid']);
            $table->index(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_assign_counter_locations');
    }
};
