<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_location_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_head_id');
            $table->foreignId('inventory_location_count_id');
            $table->string('path');
            $table->string('type')->nullable();
            $table->foreignId('user_id');

            $table->index(['inventory_head_id']);
            $table->index(['inventory_location_count_id']);
            $table->index(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_location_evidences');
    }
};
